import { BrowserRouter, Routes, Route, useLocation } from "react-router-dom";

import "bootstrap/dist/css/bootstrap.min.css";
import Nav from "./Components/Nav";
import Footer from "./Components/Footer";
import Login from "./Pages/Login/Login";
import Recuperar from "./Pages/Login/Recuperar";
// Módulo Administrador
import DeshboardAdmin from "./Components/DeshboardAdmin";
import Usuarios from "./Pages/Administrador/Usuarios";
import Autorizaciones from "./Pages/Administrador/Autorizacion";
import LogErrores from "./Pages/Administrador/LogErrores";

// Módulo Optometra
import HistoriaClinica from "./Pages/Optometra/HistoriaClinica";
import AgregarConsulta from "./Pages/Optometra/AgregarConsulta";
import GenerarFormula from "./Pages/Optometra/GenerarFormula";
import Antecedentes from "./Pages/Optometra/Antecedentes";

function AppRoutes() {
  const location = useLocation();
  const paginasSinNav = ["/login", "/recuperar"];

  const ocultarNav = paginasSinNav.includes(location.pathname);
  return (
    <div className="app">
      {!ocultarNav && <Nav />}

      <main className="contenido">
        <Routes>
          <Route path="/" element={<DeshboardAdmin />} />
          <Route path="/usuarios" element={<Usuarios />} />
          <Route path="/autorizaciones" element={<Autorizaciones />} />
          <Route path="/log-errores" element={<LogErrores />} />
          <Route path="/login" element={<Login />} />
          <Route path="/recuperar" element={<Recuperar />} />
          <Route   path="/optometra/historia-clinica" element={<HistoriaClinica />} />
          <Route  path="/optometra/agregar-consulta" element={<AgregarConsulta />} />  
          <Route path="/optometra/generar-formula" element={<GenerarFormula />} />
          <Route path="/optometra/antecedentes" element={<Antecedentes />} />
        </Routes>
      </main>

      {!ocultarNav && <Footer />}
    </div>
  );
}

function App() {
  return (
    <BrowserRouter>
      <AppRoutes />
    </BrowserRouter>
  );
}

export default App;
