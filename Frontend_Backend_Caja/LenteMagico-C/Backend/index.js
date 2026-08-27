import 'dotenv/config';
import express from 'express';
import cors from 'cors';

import AgendarConsultaRoutes from './Routes/AgendarConsulta.js';
import AgregarProductosRoutes from './Routes/AgregarProductos.js';
import CantidadProductosVendidosRoutes from './Routes/CantidadProductosVendidos.js';
import ConfirmacionBancoRoutes from './Routes/ConfirmacionBanco.js';
import ConfirmarVentaRoutes from './Routes/ConfirmarVenta.js';
import ConsultarClienteRoutes from './Routes/ConsultarCliente.js';
import ConsultasRoutes from './Routes/Consultas.js';
import EfectivoRoutes from './Routes/Efectivo.js';
import FormasPagoRoutes from './Routes/FormasPago.js';
import InicioRoutes from './Routes/Inicio.js';
import IniciooRoutes from './Routes/Inicioo.js';
import LoginRoutes from './Routes/Login.js';
import PlataformasRoutes from './Routes/Plataformas.js';
import PrecioCadaProductoRoutes from './Routes/PrecioCadaProducto.js';
import RegistrarDatosClienteRoutes from './Routes/RegistrarDatosCliente.js';
import RegistrarServicioRoutes from './Routes/RegistrarServicio.js';
import TarjetaCreditoRoutes from './Routes/TarjetaCredito.js';
import TarjetaDebitoRoutes from './Routes/TarjetaDebito.js';
import VisualizarProductoVendidoRoutes from './Routes/VisualizarProductoVendido.js';
import VisualizarVentaRoutes from './Routes/VisualizarVenta.js';
import TipoDocumentoRoutes from './Routes/TipoDocumento.js';


const app = express();

const PORT = process.env.PORT || 5000;

app.use(cors());
app.use(express.json());

app.get('/', (req, res) => {
  res.json({
    mensaje: 'Hola, estoy en el backend de Lente Mágico',
    estado: 'Servidor funcionando correctamente'
  });
});

app.use('/api/agendar-consulta', AgendarConsultaRoutes);
app.use('/api/productos', AgregarProductosRoutes);
app.use('/api/cantidad-productos-vendidos', CantidadProductosVendidosRoutes);
app.use('/api/confirmacion-banco', ConfirmacionBancoRoutes);
app.use('/api/confirmar-venta', ConfirmarVentaRoutes);
app.use('/api/clientes', ConsultarClienteRoutes);
app.use('/api/consultas', ConsultasRoutes);
app.use('/api/pagos/efectivo', EfectivoRoutes);
app.use('/api/formas-pago', FormasPagoRoutes);
app.use('/api/inicio', InicioRoutes);
app.use('/api/inicioo', IniciooRoutes);
app.use('/api/auth', LoginRoutes);
app.use('/api/pagos/plataformas', PlataformasRoutes);
app.use('/api/precio-producto', PrecioCadaProductoRoutes);
app.use('/api/registrar-cliente', RegistrarDatosClienteRoutes);
app.use('/api/servicios', RegistrarServicioRoutes);
app.use('/api/pagos/tarjeta-credito', TarjetaCreditoRoutes);
app.use('/api/pagos/tarjeta-debito', TarjetaDebitoRoutes);
app.use('/api/productos-vendidos', VisualizarProductoVendidoRoutes);
app.use('/api/ventas', VisualizarVentaRoutes);
app.use('/api/tipo-documento', TipoDocumentoRoutes);

app.use((req, res) => {
  res.status(404).json({
    error: 'Ruta no encontrada',
    ruta: req.originalUrl
  });
});

app.use((error, req, res, next) => {
  console.error('Error del servidor:', error);

  res.status(500).json({
    error: error.message || 'Error interno del servidor'
  });
});

app.listen(PORT, () => {
  console.log(`Servidor del backend escuchando en http://localhost:${PORT}`);
});