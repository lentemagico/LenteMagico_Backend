import { Router } from 'express';

const router = Router();

router.get('/', (req, res) => {

  res.json({
    mensaje: 'Backend Lente Mágico funcionando correctamente'
  });

});

export default router;