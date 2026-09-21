import 'dotenv/config';
import express from 'express';
import cors from 'cors';
import productosRoutes from './routes/productos.js';
import pool from './db.js';

const app = express();

const PORT = process.env.PORT || 5000;

app.use(cors());

app.use(express.json());


app.get('/', (req, res) => {
    res.send('Hola, estoy en el backend de Bodega');
});


app.get('/api/test-db', async (req, res) => {

    try {

        const [rows] = await pool.query(
            'SELECT 1 AS conectado'
        );

        res.json({
            mensaje: 'Base de datos conectada correctamente',
            resultado: rows
        });

    } catch (error) {

        console.error(
            'Error conectando a MySQL:',
            error
        );

        res.status(500).json({
            mensaje: 'Error al conectar con la base de datos',
            error: error.message
        });
    }
});



app.use(
    '/api/productos',
    productosRoutes
);



app.listen(PORT, () => {

    console.log(
        `Servidor del backend escuchando en http://localhost:${PORT}`
    );

});