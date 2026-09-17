// backend/routes/productos.js
import express from 'express';
import pool from '../db.js';

const router = express.Router();

// GET /api/productos — trae todos los productos
router.get('/', async (req, res) => {
    try {
        const [rows] = await pool.query('SELECT * FROM Producto');
        res.json(rows);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al obtener productos' });
    }
});

// GET /api/productos/:id
router.get('/:id', async (req, res) => {
    try {
        const { id } = req.params;
        const [rows] = await pool.query('SELECT * FROM Producto WHERE id_producto = ?', [id]);
        if (rows.length === 0) {
            return res.status(404).json({ error: 'Producto no encontrado' });
        }
        res.json(rows[0]);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al obtener el producto' });
    }
});

// POST /api/productos — crea un producto nuevo
router.post('/', async (req, res) => {
    try {
        const {
            id_categoria, codigo_producto, nombre, descripcion,
            precio_venta, estado, stock_actual, stock_minimo
        } = req.body;

        const [result] = await pool.query(
            `INSERT INTO Producto 
             (id_categoria, codigo_producto, nombre, descripcion, precio_venta, estado, stock_actual, stock_minimo) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
            [id_categoria, codigo_producto, nombre, descripcion, precio_venta, estado, stock_actual, stock_minimo]
        );

        const [nuevo] = await pool.query('SELECT * FROM Producto WHERE id_producto = ?', [result.insertId]);
        res.status(201).json(nuevo[0]);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al crear producto' });
    }
});

// PUT /api/productos/:id — actualiza un producto (usado para sumar stock)
router.put('/:id', async (req, res) => {
    try {
        const { id } = req.params;
        const {
            id_categoria, codigo_producto, nombre, descripcion,
            precio_venta, estado, stock_actual, stock_minimo
        } = req.body;

        const [existente] = await pool.query('SELECT * FROM Producto WHERE id_producto = ?', [id]);
        if (existente.length === 0) {
            return res.status(404).json({ error: 'Producto no encontrado' });
        }

        await pool.query(
            `UPDATE Producto SET 
             id_categoria = ?, codigo_producto = ?, nombre = ?, descripcion = ?, 
             precio_venta = ?, estado = ?, stock_actual = ?, stock_minimo = ? 
             WHERE id_producto = ?`,
            [id_categoria, codigo_producto, nombre, descripcion, precio_venta, estado, stock_actual, stock_minimo, id]
        );

        const [actualizado] = await pool.query('SELECT * FROM Producto WHERE id_producto = ?', [id]);
        res.json(actualizado[0]);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al actualizar producto' });
    }
});

// DELETE /api/productos/:id
router.delete('/:id', async (req, res) => {
    try {
        const { id } = req.params;
        const [existente] = await pool.query('SELECT * FROM Producto WHERE id_producto = ?', [id]);
        if (existente.length === 0) {
            return res.status(404).json({ error: 'Producto no encontrado' });
        }
        await pool.query('DELETE FROM Producto WHERE id_producto = ?', [id]);
        res.json(existente[0]);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al eliminar producto' });
    }
});

export default router;