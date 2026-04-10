import { apiClient } from './apiClient';

/**
 * Obtiene el total de activos asignados a un resguardante.
 * @param {string} usuarioId - UUID del usuario
 * @returns {Promise<number>}
 */
export const fetchActivosAsignados = async (usuarioId) => {
  const res = await apiClient(`/activos/?usuario_id=${usuarioId}`);
  return res.total ?? (res.data?.length ?? 0);
};

/**
 * Obtiene el total de auditorías asignadas a un resguardante.
 * @param {string} usuarioId - UUID del usuario
 * @returns {Promise<number>}
 */
export const fetchAuditoriasAsignadas = async (usuarioId) => {
  const res = await apiClient(`/auditorias/?usuario_id=${usuarioId}`);
  return res.total ?? (res.data?.length ?? 0);
};

/**
 * Obtiene ambos conteos en paralelo.
 * @param {string} usuarioId - UUID del usuario
 * @returns {Promise<{ activos: number, auditorias: number }>}
 */
export const fetchPerfilStats = async (usuarioId) => {
  const [activos, auditorias] = await Promise.all([
    fetchActivosAsignados(usuarioId),
    fetchAuditoriasAsignadas(usuarioId),
  ]);
  return { activos, auditorias };
};
