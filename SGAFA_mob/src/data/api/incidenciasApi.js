import { apiClient } from './apiClient';

export const postIncidencia = async (incidenciaData) => {
  return await apiClient('/buzon', {
    method: 'POST',
    body: JSON.stringify(incidenciaData),
  });
};
