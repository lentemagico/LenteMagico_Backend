import 'dotenv/config';
import express from 'express';
import cors from 'cors';
import pool from './db.js';

import loginRoutes from './routes/login.js';
import recuperarRoutes from './routes/recuperar.js';

import loginRoutes from './routes/login.js';
import recuperarRoutes from './routes/recuperar.js';
import autorizacionesRoutes from './routes/Administrador/autorizaciones.js';
import LogErroresRoutes from './routes/Administrador/logErrores.js';
import usuariosRoutes from './routes/Administrador/usuarios.js';
import tiposDocumentoRoutes from './routes/tiposDocumento.js';

const app = express();//Permite inicilizar las aplicaciones y configurar las urls
const PORT = process.env.PORT || 5000; // configuracion del puerto del backend 

app.use(cors({
  origin: 'http://localhost:5173',
  credentials: true
}));
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
app.use('/api/login', loginRoutes);
app.use('/api/recuperar', recuperarRoutes);
app.use('/api/administrador/autorizaciones', autorizacionesRoutes);
app.use('/api/administrador/logErrores', LogErroresRoutes);
app.use('/api/administrador/usuarios', usuariosRoutes);
app.use('/api/administrador/tipos-documento', tiposDocumentoRoutes);

app.listen(PORT, () => {
  console.log(`Servidor del backend escuchado en http://localhost:${PORT}`);
});


