import 'dotenv/config';
import express from 'express';
import cors from 'cors';
import pool from './db.js';

import categoriasRoutes from './routes/categorias.js';
import comprasRoutes from './routes/compras.js';
import loginRoutes from './routes/login.js';
import proveedoresRoutes from './routes/proveedores.js';
import productosRoutes from './routes/productos.js';
const app = express();//Permite inicilizar las aplicaciones y configurar las urls
const PORT = process.env.PORT || 5000; // configuracion del puerto del backend 

app.use(cors()); // Permite las solicitudes desde cualquier origen
app.use(express.json()); //Por si tiene que leer algun formato JSON


app.get('/', (req, res) => {
    res.send('Hola estoy en el backend');
});
/*
app.get('/api/mensaje', ( req, res) => {
   res.json({ mensaje: '¡ Conexion Existosa ! el backend responde correctamente' });
});
*/
//app.get('api/clientes', ( req, res ) => res.send('prueba'));
app.use('/api/categorias', categoriasRoutes);
app.use('/api/compras', comprasRoutes);
app.use('/api/login', loginRoutes);
app.use('/api/proveedores', proveedoresRoutes);
app.use('/api/productos', productosRoutes); 



app.listen(PORT, () => {
    console.log(`Servidor del backen escuchado en http://localhost:${PORT}`);
});