import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

const URL_USUARIOS = "/api/administrador/usuarios";
const URL_AUTORIZACIONES = "/api/administrador/autorizaciones";
const URL_LOG_ERRORES = "/api/administrador/logErrores";

function DeshboardAdmin() {
  const [usuarios, setUsuarios] = useState([]);
  const [roles, setRoles] = useState([]);
  const [logs, setLogs] = useState([]);

  const [cargando, setCargando] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    async function cargarDashboard() {
      try {
        setCargando(true);
        setError("");

        const [respuestaUsuarios, respuestaRoles, respuestaLogs] =
          await Promise.all([
            fetch(URL_USUARIOS),
            fetch(URL_AUTORIZACIONES),
            fetch(URL_LOG_ERRORES),
          ]);

        if (!respuestaUsuarios.ok || !respuestaRoles.ok || !respuestaLogs.ok) {
          throw new Error("No fue posible cargar la información del panel.");
        }

        const dataUsuarios = await respuestaUsuarios.json();
        const dataRoles = await respuestaRoles.json();
        const dataLogs = await respuestaLogs.json();

        setUsuarios(Array.isArray(dataUsuarios) ? dataUsuarios : []);
        setRoles(Array.isArray(dataRoles) ? dataRoles : []);
        setLogs(Array.isArray(dataLogs) ? dataLogs : []);
      } catch (err) {
        console.error("Error al cargar dashboard:", err);
        setError(err.message);
      } finally {
        setCargando(false);
      }
    }

    cargarDashboard();
  }, []);

  const fechaHoy = new Date().toISOString().slice(0, 10);

  const erroresCriticosHoy = logs.filter((log) => {
    const nivel = (log.nivel || "").toLowerCase();
    const fechaLog = String(log.fecha || "").slice(0, 10);

    return (
      (nivel === "crítico" || nivel === "critico") && fechaLog === fechaHoy
    );
  });

  const ultimosUsuarios = usuarios.slice(0, 5);

  return (
    <div className="container mt-4 mb-5">
      <div className="mb-4">
        <h1 className="fw-bold">Bienvenido al Módulo de Administrador</h1>

        <p className="text-muted fs-5">
          Gestiona accesos, roles de usuario y registros del sistema.
        </p>
      </div>

      {error && <div className="alert alert-danger">{error}</div>}

      <div className="row g-3 mb-4">
        <div className="col-md-6 col-lg-3">
          <div className="card shadow-sm h-100 border-start border-4 border-primary">
            <div className="card-body">
              <p className="text-muted mb-2">Usuarios registrados</p>
              <h2 className="fw-bold mb-0">
                {cargando ? "..." : usuarios.length}
              </h2>
            </div>
          </div>
        </div>

        <div className="col-md-6 col-lg-3">
          <div className="card shadow-sm h-100 border-start border-4 border-success">
            <div className="card-body">
              <p className="text-muted mb-2">Roles disponibles</p>
              <h2 className="fw-bold mb-0">
                {cargando ? "..." : roles.length}
              </h2>
            </div>
          </div>
        </div>

        <div className="col-md-6 col-lg-3">
          <div className="card shadow-sm h-100 border-start border-4 border-danger">
            <div className="card-body">
              <p className="text-muted mb-2">Errores críticos hoy</p>
              <h2 className="fw-bold mb-0">
                {cargando ? "..." : erroresCriticosHoy.length}
              </h2>
            </div>
          </div>
        </div>

        <div className="col-md-6 col-lg-3">
          <div className="card shadow-sm h-100 border-start border-4 border-warning">
            <div className="card-body">
              <p className="text-muted mb-2">Logs del sistema</p>
              <h2 className="fw-bold mb-0">{cargando ? "..." : logs.length}</h2>
            </div>
          </div>
        </div>
      </div>

      <h3 className="fw-bold mb-3">Acciones rápidas</h3>

      <div className="row g-3 mb-4">
        <div className="col-md-4">
          <Link
            to="/usuarios"
            className="card shadow-sm h-100 text-decoration-none text-dark"
          >
            <div className="card-body">
              <h5 className="fw-bold">Gestionar usuarios</h5>
              <p className="mb-0 text-muted">
                Crear, editar o desactivar cuentas de acceso.
              </p>
            </div>
          </Link>
        </div>

        <div className="col-md-4">
          <Link
            to="/autorizaciones"
            className="card shadow-sm h-100 text-decoration-none text-dark"
          >
            <div className="card-body">
              <h5 className="fw-bold">Configurar roles</h5>
              <p className="mb-0 text-muted">
                Administrar permisos y autorizaciones.
              </p>
            </div>
          </Link>
        </div>

        <div className="col-md-4">
          <Link
            to="/log-errores"
            className="card shadow-sm h-100 text-decoration-none text-dark"
          >
            <div className="card-body">
              <h5 className="fw-bold">Log de errores</h5>
              <p className="mb-0 text-muted">
                Consultar fallos y registros del sistema.
              </p>
            </div>
          </Link>
        </div>
      </div>

      <div className="card shadow-sm">
        <div className="card-body p-4">
          <h3 className="fw-bold mb-3">Últimos usuarios registrados</h3>

          <div className="table-responsive">
            <table className="table align-middle">
              <thead className="table-light">
                <tr>
                  <th>#ID</th>
                  <th>Usuario</th>
                  <th>Correo</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>

              <tbody>
                {cargando && (
                  <tr>
                    <td colSpan="5" className="text-center text-muted py-4">
                      Cargando información...
                    </td>
                  </tr>
                )}

                {!cargando &&
                  ultimosUsuarios.map((usuario) => (
                    <tr key={usuario.id}>
                      <td>{usuario.id}</td>

                      <td>
                        {[
                          usuario.primer_nombre,
                          usuario.segundo_nombre,
                          usuario.primer_apellido,
                          usuario.segundo_apellido,
                        ]
                          .filter(Boolean)
                          .join(" ") || "Sin nombre"}
                      </td>

                      <td>{usuario.correo || "-"}</td>

                      <td>
                        <span
                          className={
                            Number(usuario.activar_usuario) === 1
                              ? "badge text-bg-success"
                              : "badge text-bg-secondary"
                          }
                        >
                          {Number(usuario.activar_usuario) === 1
                            ? "Activo"
                            : "Inactivo"}
                        </span>
                      </td>

                      <td>
                        <Link
                          to="/usuarios"
                          className="btn btn-sm btn-outline-primary"
                        >
                          Ver usuarios
                        </Link>
                      </td>
                    </tr>
                  ))}

                {!cargando && ultimosUsuarios.length === 0 && (
                  <tr>
                    <td colSpan="5" className="text-center text-muted py-4">
                      No se encontraron registros de usuarios en el servidor.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}

export default DeshboardAdmin;
