import { useState } from 'react';
import { apiClient } from '../../data/api/apiClient';

export const useEscaner = () => {
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState(null);

  // Consulta los detalles completos del activo usando la búsqueda por Código
  const lookupActivo = async (codigoQR) => {
    setIsLoading(true);
    setError(null);
    try {
      const res = await apiClient(`/activos?search=${codigoQR}`);
      if (res.data && res.data.length > 0) {
        return res.data[0];
      } else {
        throw new Error("El activo escaneado no existe o no tiene registro.");
      }
    } catch (err) {
      setError(err.message);
      throw err;
    } finally {
      setIsLoading(false);
    }
  };

  // Actualiza el activo y, opcionalmente, marca el progreso de la auditoría
  const submitScannedActivo = async (assetId, payload, auditoriaId = null) => {
    setIsLoading(true);
    setError(null);
    try {
      // 1. Actualizar Activo
      await apiClient(`/activos/${assetId}`, {
        method: 'PUT',
        body: JSON.stringify(payload)
      });

      // 2. Si venimos del flujo de Auditoría, marcar progreso
      if (auditoriaId) {
        await apiClient(`/auditorias/${auditoriaId}/escanear`, {
          method: 'PUT',
          body: JSON.stringify({ activo_id: assetId })
        });
      }
      return true;
    } catch (err) {
      setError(err.message);
      throw err;
    } finally {
      setIsLoading(false);
    }
  };

  return { lookupActivo, submitScannedActivo, isLoading, error, setError };
};
