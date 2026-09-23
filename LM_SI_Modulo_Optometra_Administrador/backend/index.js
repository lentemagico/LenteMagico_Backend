import 'dotenv/config';
import express from 'express';
import cors from 'cors';
import pool from './db.js';

import loginRoutes from './routes/login.js';
import recuperarRoutes from './routes/recuperar.js';
import autorizacionesRoutes from './routes/autorizaciones.js';
import LogErroresRoutes from './routes/logErrores.js';
import usuariosRoutes from './routes/usuarios.js';
import tiposDocumentoRoutes from './routes/tiposDocumento.js';
import agregarConsultaRoutes from "./routes/agregarConsulta.js";
import historiaClinicaRoutes from "./routes/historiaClinica.js";
import antecedentesRoutes from './routes/antecedentes.js';
import generarFormulaRoutes from './routes/generarFormula.js';

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
app.use('/api/login', loginRoutes);
app.use('/api/recuperar', recuperarRoutes);
app.use('/api/administrador/autorizaciones', autorizacionesRoutes);
app.use('/api/administrador/logErrores', LogErroresRoutes);
app.use('/api/administrador/usuarios', usuariosRoutes);
app.use('/api/administrador/tipos-documento', tiposDocumentoRoutes);

app.use('/api/optometra', agregarConsultaRoutes);
app.use('/api/optometra', antecedentesRoutes);
app.use('/api/optometra', generarFormulaRoutes);
app.use('/api/optometra', historiaClinicaRoutes);

app.listen(PORT, () => {
  console.log(`Servidor del backen escuchado en http://localhost:${PORT}`);
});


