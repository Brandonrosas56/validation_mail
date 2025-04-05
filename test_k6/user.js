import http from 'k6/http';
import { check } from 'k6';
import { sleep } from 'k6';

export default function () {
  const res = http.get('http://127.0.0.1:8000/show-account'); // Cambia esto por la URL de tu aplicación
  check(res, {
    'status es 200': (r) => r.status === 200,
  });

  sleep(1); // Pausa de 1 segundo entre cada solicitud
}

export let options = {
  stages: [
    { duration: '1m', target: 10 },  // Aumenta hasta 10 usuarios en 1 minuto
    { duration: '3m', target: 10 },  // Mantiene 10 usuarios durante 3 minutos
    { duration: '1m', target: 0 },   // Reduce gradualmente a 0 usuarios en 1 minuto
  ],
};
