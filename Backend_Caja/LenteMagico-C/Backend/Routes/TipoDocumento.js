import { Router } from 'express';
import pool from '../db.js';

const router = Router();

router.get('/', async (req, res) => {
    try {
        const [rows] = await pool.query(
            "SELECT id, sigla, nombre_documento FROM Tipo_documento WHERE estado = 'activo'"
        );
        res.json(rows);
    } catch (error) {
        console.error('Error al obtener tipos de documento:', error);
        res.status(500).json({ error: error.message });
    }
});

export default router;