import { apiClient } from './apiClient';

const API = '/auth';

/**
 * Iniciar sesión — retorna { access_token, token_type, user }
 */
export const loginApi = (email, password) =>
  apiClient(`${API}/login`, {
    method: 'POST',
    body: JSON.stringify({ email, password }),
  });

/**
 * Registrar nuevo usuario — retorna UserResponse
 */
export const registerApi = (nombre, apellidos, email, password, rol_id = 2) =>
  apiClient(`${API}/register`, {
    method: 'POST',
    body: JSON.stringify({ nombre, apellidos, email, password, rol_id }),
  });

/**
 * Obtener perfil del usuario autenticado
 */
export const getMeApi = () =>
  apiClient(`${API}/me`);

/**
 * Cerrar sesión (invalida el token en BD)
 */
export const logoutApi = () =>
  apiClient(`${API}/logout`, { method: 'POST' });
