import express from "express";
import pool from "../db.js";

const router = express.Router();

router.post("/", async (req, res) => {
  try {
    const {
      id_cliente,
      id_usuario,
      id_historia,
      motivo,
      resultado_examen,
      diagnostico,
      recomendaciones,
    } = req.body;

    if (
      !id_cliente ||
      !id_usuario ||
      !id_historia ||
      !motivo
    ) {
      return res.status(400).json({
        error: "Faltan datos obligatorios para registrar la consulta.",
      });
    }

    const sql = `
      INSERT INTO Consulta (
        id_cliente,
        id_usuario,
        id_historia,
        motivo,
        resultado_examen,
        diagnostico,
        recomendaciones
      )
      VALUES (?, ?, ?, ?, ?, ?, ?)
    `;

    const [resultado] = await pool.query(sql, [
      id_cliente,
      id_usuario,
      id_historia,
      motivo,
      resultado_examen || "Sin resultado registrado",
      diagnostico || "Sin diagnóstico registrado",
      recomendaciones || "Sin recomendaciones",
    ]);

    res.status(201).json({
      mensaje: "Consulta registrada correctamente.",
      id_consulta: resultado.insertId,
    });
  } catch (error) {
    console.error("Error al registrar consulta:", error);

    res.status(500).json({
      error: "Error al registrar la consulta.",
    });
  }s
});

export default router;