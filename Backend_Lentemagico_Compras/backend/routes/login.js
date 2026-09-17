import { Router } from 'express';
import pool from '../db.js';

const router = Router();

router.post('/', async (req, res) => {
    try {
        const { correo, contrasenia } = req.body;

        if (!correo || !contrasenia) {
            return res.status(400).json({
                mensaje: 'Correo y contraseña son campos requeridos'
            });
        }

        const [rows] = await pool.query(
            `SELECT u.*, dp.correo, dp.primer_nombre, dp.primer_apellido
             FROM Usuario u
             JOIN datos_personales dp ON u.id_datos_personales = dp.id
             WHERE dp.correo = ? AND u.contrasenia = ?`,
            [correo, contrasenia]
        );

        if (rows.length === 0) {
            return res.status(401).json({ mensaje: 'Correo electrónico o contraseña incorrectos.' });
        }

        const usuario = rows[0];

        if (usuario.activar_usuario === 0) {
            return res.status(403).json({ mensaje: 'Usuario inactivo' });
        }

        res.json({
            mensaje: 'Login exitoso',
            usuario,
            token: null,
            rol: 'usuario'
        });
    } catch (error) {
        console.error('Error en login:', error);
        res.status(500).json({ mensaje: 'Error de conexión con el servidor de autenticación.' });
    }
});

export default router;