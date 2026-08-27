import { Router } from 'express';
import pool from '../db.js';

const router = Router();


// Obtener consultas
router.get('/', async (req, res) => {

  try {

    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 5;
    const search = req.query.search || '';
    const offset = (page - 1) * limit;

    let query = `
      SELECT
        c.*,
        dp.numero_documento,
        CONCAT(
          dp.primer_nombre,
          ' ',
          dp.primer_apellido
        ) AS cliente
      FROM Consulta c
      INNER JOIN Cliente cl
        ON c.id_cliente = cl.id_cliente
      INNER JOIN Datos_personales dp
        ON cl.id_datos_personales = dp.id
    `;

    let countQuery = `
      SELECT COUNT(*) AS total
      FROM Consulta c
      INNER JOIN Cliente cl
        ON c.id_cliente = cl.id_cliente
      INNER JOIN Datos_personales dp
        ON cl.id_datos_personales = dp.id
    `;

    const params = [];

    if (search) {

      const condition = `
        WHERE dp.numero_documento LIKE ?
        OR dp.primer_nombre LIKE ?
        OR dp.primer_apellido LIKE ?
        OR c.motivo LIKE ?
        OR c.diagnostico LIKE ?
      `;

      query += condition;
      countQuery += condition;

      const s = `%${search}%`;

      params.push(s, s, s, s, s);
    }

    query += `
      ORDER BY c.fecha_hora DESC
      LIMIT ? OFFSET ?
    `;

    const [rows] = await pool.query(
      query,
      [...params, limit, offset]
    );

    const [count] = await pool.query(
      countQuery,
      params
    );

    const total = count[0].total;
    const totalPages = Math.ceil(total / limit);

    res.json({
      consultas: rows,
      pagination: {
        currentPage: page,
        totalPages,
        totalItems: total,
        limit,
        hasNextPage: page < totalPages,
        hasPrevPage: page > 1
      }
    });

  } catch (error) {

    res.status(500).json({
      error: error.message
    });

  }

});


// Obtener consulta
router.get('/:id', async (req, res) => {

  try {

    const [rows] = await pool.query(`
      SELECT *
      FROM Consulta
      WHERE id_consulta = ?
    `, [req.params.id]);

    if (rows.length === 0) {

      return res.status(404).json({
        error: 'Consulta no encontrada'
      });

    }

    res.json(rows[0]);

  } catch (error) {

    res.status(500).json({
      error: error.message
    });

  }

});


// Crear consulta
router.post('/', async (req, res) => {

  try {

    const {
      id_cliente,
      id_usuario,
      id_historia,
      fecha_hora,
      motivo,
      resultado_examen,
      diagnostico,
      recomendaciones
    } = req.body;

    if (
      !id_cliente ||
      !id_usuario ||
      !id_historia ||
      !motivo ||
      !resultado_examen ||
      !diagnostico ||
      !recomendaciones
    ) {

      return res.status(400).json({
        error: 'Faltan campos obligatorios'
      });

    }

    const [result] = await pool.query(`
      INSERT INTO Consulta (
        id_cliente,
        id_usuario,
        id_historia,
        fecha_hora,
        motivo,
        resultado_examen,
        diagnostico,
        recomendaciones
      )
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    `, [
      id_cliente,
      id_usuario,
      id_historia,
      fecha_hora || null,
      motivo,
      resultado_examen,
      diagnostico,
      recomendaciones
    ]);

    res.status(201).json({
      mensaje: 'Consulta registrada exitosamente',
      id_consulta: result.insertId
    });

  } catch (error) {

    res.status(500).json({
      error: error.message
    });

  }

});


// Actualizar consulta
router.put('/:id', async (req, res) => {

  try {

    const {
      id_cliente,
      id_usuario,
      id_historia,
      fecha_hora,
      motivo,
      resultado_examen,
      diagnostico,
      recomendaciones
    } = req.body;

    const [result] = await pool.query(`
      UPDATE Consulta
      SET
        id_cliente = ?,
        id_usuario = ?,
        id_historia = ?,
        fecha_hora = ?,
        motivo = ?,
        resultado_examen = ?,
        diagnostico = ?,
        recomendaciones = ?
      WHERE id_consulta = ?
    `, [
      id_cliente,
      id_usuario,
      id_historia,
      fecha_hora,
      motivo,
      resultado_examen,
      diagnostico,
      recomendaciones,
      req.params.id
    ]);

    if (result.affectedRows === 0) {

      return res.status(404).json({
        error: 'Consulta no encontrada'
      });

    }

    res.json({
      mensaje: 'Consulta actualizada exitosamente'
    });

  } catch (error) {

    res.status(500).json({
      error: error.message
    });

  }

});


// Eliminar consulta
router.delete('/:id', async (req, res) => {

  try {

    const [result] = await pool.query(`
      DELETE FROM Consulta
      WHERE id_consulta = ?
    `, [req.params.id]);

    if (result.affectedRows === 0) {

      return res.status(404).json({
        error: 'Consulta no encontrada'
      });

    }

    res.json({
      mensaje: 'Consulta eliminada exitosamente'
    });

  } catch (error) {

    res.status(500).json({
      error: error.message
    });

  }

});

export default router;