import express from 'express';
import pool from '../db.js';

const router = express.Router();

// GET /api/compras — trae compras con su detalle anidado
router.get('/', async (req, res) => {
    try {
        const [compras] = await pool.query(
            `SELECT c.*, p.razon_social AS idProveedor 
             FROM Compra c 
             LEFT JOIN Proveedor p ON c.id_proveedor = p.id_proveedor`
        );

        for (const compra of compras) {
            const [detalle] = await pool.query(
                `SELECT dc.*, pr.nombre AS nombreProducto 
                 FROM Detalle_compra dc 
                 LEFT JOIN Producto pr ON dc.id_producto = pr.id_producto 
                 WHERE dc.id_compra = ?`,
                [compra.id_compra]
            );

            compra.detalle = detalle.map(d => ({
                nombreProducto: d.nombreProducto,
                cantidad: d.cantidad,
                costoUnitario: d.costo_unitario
            }));

            compra.id = compra.id_compra;
            compra.fechaCompra = compra.fecha_compra;
            compra.numComprobante = compra.num_comprobante;
        }

        res.json(compras);
    } catch (error) {
        console.error('Error al obtener compras:', error);
        res.status(500).json({
            error: 'Error al obtener compras'
        });
    }
});


// GET /api/compras/:id
router.get('/:id', async (req, res) => {
    try {
        const { id } = req.params;

        const [rows] = await pool.query(
            'SELECT * FROM Compra WHERE id_compra = ?',
            [id]
        );

        if (rows.length === 0) {
            return res.status(404).json({
                error: 'Compra no encontrada'
            });
        }

        res.json(rows[0]);

    } catch (error) {
        console.error('Error al obtener la compra:', error);

        res.status(500).json({
            error: 'Error al obtener la compra'
        });
    }
});


// POST /api/compras
// Crea la compra, detalle y productos nuevos.
// Si el producto ya existe, utiliza el producto existente.
// Si no existe, lo crea utilizando idCategoria enviado desde React.
router.post('/', async (req, res) => {
    const conn = await pool.getConnection();

    try {
        await conn.beginTransaction();

        const {
            idProveedor,
            fechaCompra,
            numComprobante,
            estado,
            detalle
        } = req.body;


        // =====================================================
        // VALIDACIONES GENERALES
        // =====================================================

        if (!idProveedor) {
            throw new Error('El proveedor es obligatorio');
        }

        if (!fechaCompra) {
            throw new Error('La fecha de compra es obligatoria');
        }

        if (!numComprobante || !numComprobante.trim()) {
            throw new Error('El número de comprobante es obligatorio');
        }

        if (!estado) {
            throw new Error('El estado de la compra es obligatorio');
        }

        if (!Array.isArray(detalle) || detalle.length === 0) {
            throw new Error(
                'La compra debe tener al menos un producto'
            );
        }


        // =====================================================
        // BUSCAR PROVEEDOR
        // =====================================================

        const [proveedores] = await conn.query(
            `SELECT id_proveedor 
             FROM Proveedor 
             WHERE razon_social = ?`,
            [idProveedor]
        );

        if (proveedores.length === 0) {
            throw new Error(
                `El proveedor "${idProveedor}" no existe`
            );
        }

        const id_proveedor = proveedores[0].id_proveedor;


        // =====================================================
        // CREAR COMPRA
        // =====================================================

        const [result] = await conn.query(
            `INSERT INTO Compra 
             (
                id_proveedor,
                fecha_compra,
                num_comprobante,
                estado
             ) 
             VALUES (?, ?, ?, ?)`,
            [
                id_proveedor,
                fechaCompra,
                numComprobante,
                estado
            ]
        );

        const id_compra = result.insertId;


        // =====================================================
        // INVENTARIO
        // =====================================================

        // Las compras anuladas no aumentan el inventario.
        const actualizaInventario = estado !== 'Anulada';


        // =====================================================
        // PROCESAR CADA PRODUCTO
        // =====================================================

        for (const linea of detalle) {

            const nombreProducto =
                linea.nombreProducto?.trim();

            const cantidad =
                Number(linea.cantidad || 0);

            const costoUnitario =
                Number(linea.costoUnitario || 0);

            const idCategoria =
                Number(linea.idCategoria || 0);


            // -------------------------------------------------
            // Validaciones del detalle
            // -------------------------------------------------

            if (!nombreProducto) {
                throw new Error(
                    'Uno de los productos no tiene nombre'
                );
            }

            if (cantidad <= 0) {
                throw new Error(
                    `La cantidad del producto "${nombreProducto}" debe ser mayor que 0`
                );
            }

            if (costoUnitario < 0) {
                throw new Error(
                    `El costo del producto "${nombreProducto}" no puede ser negativo`
                );
            }


            // =================================================
            // BUSCAR PRODUCTO EXISTENTE
            // =================================================

            const [productosExistentes] = await conn.query(
                `SELECT 
                    id_producto,
                    id_categoria,
                    nombre,
                    stock_actual
                 FROM Producto
                 WHERE LOWER(TRIM(nombre)) = LOWER(TRIM(?))
                 LIMIT 1`,
                [nombreProducto]
            );


            let id_producto;


            // =================================================
            // PRODUCTO EXISTENTE
            // =================================================

            if (productosExistentes.length > 0) {

                const producto =
                    productosExistentes[0];

                id_producto =
                    producto.id_producto;


                // Si la compra no está anulada,
                // aumenta el stock.
                if (actualizaInventario) {

                    const nuevoStock =
                        Number(producto.stock_actual || 0) +
                        cantidad;

                    await conn.query(
                        `UPDATE Producto 
                         SET stock_actual = ?
                         WHERE id_producto = ?`,
                        [
                            nuevoStock,
                            id_producto
                        ]
                    );
                }


                // =================================================
                // PRODUCTO NUEVO
                // =================================================

            } else {

                // La categoría es obligatoria
                // únicamente porque estamos creando
                // un producto nuevo.

                if (!idCategoria) {
                    throw new Error(
                        `El producto "${nombreProducto}" no tiene una categoría seleccionada`
                    );
                }


                // ---------------------------------------------
                // Verificar que la categoría exista
                // ---------------------------------------------

                const [categorias] = await conn.query(
                    `SELECT id_categoria
                     FROM Cat_producto
                     WHERE id_categoria = ?
                     LIMIT 1`,
                    [idCategoria]
                );

                if (categorias.length === 0) {
                    throw new Error(
                        `La categoría con ID ${idCategoria} no existe`
                    );
                }


                // ---------------------------------------------
                // Generar código del producto
                // ---------------------------------------------

                const codigo_producto =
                    `PRD-${Date.now()}-${Math.floor(Math.random() * 1000)}`;


                // ---------------------------------------------
                // Stock inicial
                // ---------------------------------------------

                const stockInicial =
                    actualizaInventario
                        ? cantidad
                        : 0;


                // ---------------------------------------------
                // Crear producto
                // ---------------------------------------------

                const [nuevoProducto] = await conn.query(
                    `INSERT INTO Producto 
                     (
                        id_categoria,
                        codigo_producto,
                        nombre,
                        descripcion,
                        precio_venta,
                        estado,
                        fecha_creacion,
                        stock_actual,
                        stock_minimo
                     ) 
                     VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)`,
                    [
                        idCategoria,
                        codigo_producto,
                        nombreProducto,
                        '',
                        costoUnitario,
                        'Activo',
                        stockInicial,
                        0
                    ]
                );

                id_producto =
                    nuevoProducto.insertId;
            }


            // =================================================
            // CREAR DETALLE DE COMPRA
            // =================================================

            await conn.query(
                `INSERT INTO Detalle_compra 
                 (
                    id_compra,
                    cantidad,
                    costo_unitario,
                    id_producto
                 ) 
                 VALUES (?, ?, ?, ?)`,
                [
                    id_compra,
                    cantidad,
                    costoUnitario,
                    id_producto
                ]
            );
        }


        // =====================================================
        // CONFIRMAR TRANSACCIÓN
        // =====================================================

        await conn.commit();


        res.status(201).json({
            message: 'Compra registrada correctamente',
            id_compra,
            ...req.body
        });


    } catch (error) {

        await conn.rollback();

        console.error(
            'Error al crear la compra:',
            error
        );

        res.status(500).json({
            error:
                error.message ||
                'Error al crear la compra'
        });

    } finally {

        conn.release();
    }
});


// DELETE /api/compras/:id
router.delete('/:id', async (req, res) => {
    try {

        const { id } = req.params;


        // Buscar compra
        const [existente] = await pool.query(
            'SELECT * FROM Compra WHERE id_compra = ?',
            [id]
        );


        if (existente.length === 0) {
            return res.status(404).json({
                error: 'Compra no encontrada'
            });
        }


        // Eliminar detalle
        await pool.query(
            'DELETE FROM Detalle_compra WHERE id_compra = ?',
            [id]
        );


        // Eliminar compra
        await pool.query(
            'DELETE FROM Compra WHERE id_compra = ?',
            [id]
        );


        res.json(existente[0]);

    } catch (error) {

        console.error(
            'Error al eliminar la compra:',
            error
        );

        res.status(500).json({
            error: 'Error al eliminar la compra'
        });
    }
});


export default router;