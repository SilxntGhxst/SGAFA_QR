import { apiClient } from './apiClient';

export const fetchAuditorias = async (usuarioId = null) => {
  const query = usuarioId ? `?usuario_id=${usuarioId}` : '';
  const response = await apiClient(`/auditorias${query}`);
  return response.data || [];
};
