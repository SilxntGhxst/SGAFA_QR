import { apiClient } from './apiClient';
import { SyncManager } from '../sync/syncManager';

export const postIncidencia = async (incidenciaData) => {
  try {
    return await apiClient('/buzon', {
      method: 'POST',
      body: JSON.stringify(incidenciaData),
    });
  } catch (error) {
    console.warn('Error postIncidencia, guardando en cola offline:', error);
    await SyncManager.addToQueue('/buzon', 'POST', incidenciaData);
    return { success: true, offline: true };
  }
};
