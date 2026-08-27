import { Router } from "express";
import pool from "../db.js";

const router = Router();


// ======================================================
// POST - LOGIN
// ======================================================

router.post("/", async (req, res) => {

  try {

    const {
      correo,
      contrasenia
    } = req.body;


    console.log("======================================");
    console.log("INTENTO DE LOGIN");
    console.log("Correo:", correo);
    console.log("======================================");


    // ==================================================
    // VALIDAR CORREO
    // ==================================================

    if (
      correo === undefined ||
      correo === null ||
      String(correo).trim() === ""
    ) {

      return res.status(400).json({

        error: "El correo es obligatorio"

      });

    }


    // ==================================================
    // VALIDAR CONTRASEÑA
    // ==================================================

    if (
      contrasenia === undefined ||
      contrasenia === null ||
      String(contrasenia).trim() === ""
    ) {

      return res.status(400).json({

        error: "La contraseña es obligatoria"

      });

    }


    const email =
      String(correo)
        .trim()
        .toLowerCase();


    const password =
      String(contrasenia);


    // ==================================================
    // BUSCAR USUARIO EN LA BASE DE DATOS
    // ==================================================

    const [rows] = await pool.query(

      `

      SELECT

        u.id,

        u.id_datos_personales,

        u.contrasenia,

        u.activar_usuario,

        dp.numero_documento,

        dp.primer_nombre,

        dp.segundo_nombre,

        dp.primer_apellido,

        dp.segundo_apellido,

        dp.correo

      FROM Usuario u

      INNER JOIN Datos_personales dp

        ON u.id_datos_personales = dp.id

      WHERE LOWER(dp.correo) = ?

      LIMIT 1

      `,

      [email]

    );


    console.log(
      "Usuarios encontrados:",
      rows.length
    );


    // ==================================================
    // CORREO NO EXISTE
    // ==================================================

    if (rows.length === 0) {

      return res.status(401).json({

        error:
          "Correo o contraseña incorrectos"

      });

    }


    const usuario = rows[0];


    console.log(
      "Usuario encontrado:",
      usuario.id
    );


    // ==================================================
    // COMPARAR CONTRASEÑA
    // ==================================================

    if (
      String(usuario.contrasenia) !== password
    ) {

      return res.status(401).json({

        error:
          "Correo o contraseña incorrectos"

      });

    }


    // ==================================================
    // COMPROBAR ESTADO
    // ==================================================

    if (

      usuario.activar_usuario === 0 ||

      usuario.activar_usuario === false ||

      usuario.activar_usuario === "0"

    ) {

      return res.status(403).json({

        error:
          "El usuario está desactivado"

      });

    }


    // ==================================================
    // ELIMINAR CONTRASEÑA
    // ==================================================

    delete usuario.contrasenia;


    // ==================================================
    // LOGIN CORRECTO
    // ==================================================

    console.log(
      "LOGIN CORRECTO - ID:",
      usuario.id
    );


    return res.status(200).json({

      mensaje:
        "Inicio de sesión exitoso",

      usuario:
        usuario

    });


  } catch (error) {


    console.error(
      "======================================"
    );

    console.error(
      "ERROR REAL EN LOGIN"
    );

    console.error(error);

    console.error(
      "======================================"
    );


    return res.status(500).json({

      error:
        "Error interno al iniciar sesión"

    });

  }

});


export default router;