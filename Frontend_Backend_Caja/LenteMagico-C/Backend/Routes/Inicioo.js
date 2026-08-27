import { Router } from "express";
import pool from "../db.js";

const router = Router();


// =====================================================
// DASHBOARD INICIOO
// =====================================================

router.get("/", async (req, res) => {

  try {

    // =================================================
    // 1. CLIENTES REGISTRADOS
    // =================================================

    const [clientesResult] = await pool.query(`
      SELECT COUNT(*) AS total
      FROM Cliente
    `);


    // =================================================
    // 2. VENTAS DEL MES
    // =================================================

    const [ventasMesResult] = await pool.query(`
      SELECT 
        COALESCE(
          SUM(
            dv.cantidad * dv.precio_unitario
          ),
          0
        ) AS total
      FROM Venta v
      INNER JOIN Detalle_venta dv
        ON v.id_venta = dv.id_venta
      WHERE MONTH(v.fecha_venta) = MONTH(CURRENT_DATE())
        AND YEAR(v.fecha_venta) = YEAR(CURRENT_DATE())
    `);


    // =================================================
    // 3. CLIENTES PROGRAMADOS
    // =================================================

    const [programadosResult] = await pool.query(`
      SELECT COUNT(*) AS total
      FROM Consulta
      WHERE DATE(fecha_hora) >= CURRENT_DATE()
    `);


    // =================================================
    // 4. ÚLTIMAS VENTAS
    // =================================================

    const [ventasResult] = await pool.query(`
      SELECT
        v.id_venta,
        v.id_cliente,
        v.fecha_venta,
        v.estado,
        COALESCE(
          SUM(
            dv.cantidad * dv.precio_unitario
          ),
          0
        ) AS total
      FROM Venta v
      LEFT JOIN Detalle_venta dv
        ON v.id_venta = dv.id_venta
      GROUP BY
        v.id_venta,
        v.id_cliente,
        v.fecha_venta,
        v.estado
      ORDER BY v.fecha_venta DESC
      LIMIT 10
    `);


    // =================================================
    // RESPUESTA
    // =================================================

    res.json({

      clientesRegistrados:
        Number(clientesResult[0].total),

      ventasMes:
        Number(ventasMesResult[0].total),

      clientesProgramados:
        Number(programadosResult[0].total),

      ultimasVentas:
        ventasResult.map((venta) => ({
          idVenta: venta.id_venta,
          idCliente: venta.id_cliente,
          fechaVenta: venta.fecha_venta,
          total: Number(venta.total),
          estado: venta.estado
        }))

    });

  } catch (error) {

    console.error(
      "Error cargando dashboard:",
      error
    );

    res.status(500).json({
      error: error.message
    });

  }

});


export default router;