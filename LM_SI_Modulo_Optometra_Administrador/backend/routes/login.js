
import { Router } from "express";
import pool from "../db.js";

const router = Router();

router.post("/", async (req, res) => {
  try {
    const { correo, contrasena } = req.body;
    if (!correo || !contrasena) {
      return res.status(400).json({
        mensaje: "Correo y contraseña son campos requeridos",
      });
    }
    const [rows] = await pool.query(
      `
      SELECT 
        u.id,
        u.contrasenia,
        u.activar_usuario,
        dp.correo,
        dp.primer_nombre,
        dp.segundo_nombre,
        dp.primer_apellido,
        dp.segundo_apellido
      FROM Usuario u
      INNER JOIN Datos_personales dp 
        ON u.id_datos_personales = dp.id
      WHERE dp.correo = ? 
        AND u.contrasenia = ?
      `,
      [correo, contrasena]
    );

    if (rows.length === 0) {
      return res.status(401).json({
        mensaje: "Correo electrónico o contraseña incorrectos.",
      });
    }

    const usuario = rows[0];

    if (usuario.activar_usuario === 0) {
      return res.status(403).json({
        mensaje: "Usuario inactivo",
      });
    }

    const nombre = [
      usuario.primer_nombre,
      usuario.segundo_nombre,
      usuario.primer_apellido,
      usuario.segundo_apellido,
    ]
      .filter(Boolean)
      .join(" ");
      
    res.status(200).json({
      mensaje: "Login exitoso",

      usuario: {
        id: usuario.id,
        nombre: nombre,
        login: usuario.correo,
        correo: usuario.correo,
      },

      token: null,
      rol: "usuario",
    });

  } catch (error) {
    console.error("Error en login:", error);

    res.status(500).json({
      mensaje: "Error en el servidor",
      error: error.message,
    });
  }
});

export default router;
