import { apiClient } from './apiClient';
import { SyncManager } from '../sync/syncManager';

export const fetchAuditorias = async (usuarioId = null) => {
  const query = usuarioId ? `?usuario_id=${usuarioId}` : '';
  const response = await apiClient(`/auditorias${query}`);
  return response.data || [];
};

export const saveResultadoAuditoria = async (auditoriaId, resultado) => {
  try {
    return await apiClient(`/auditorias/${auditoriaId}`, {
      method: 'PUT',
      body: JSON.stringify(resultado),
    });
  } catch (error) {
    console.warn('Error guardando auditoria, a cola offline:', error);
    await SyncManager.addToQueue(`/auditorias/${auditoriaId}`, 'PUT', resultado);
    return { success: true, offline: true };
  }
};
