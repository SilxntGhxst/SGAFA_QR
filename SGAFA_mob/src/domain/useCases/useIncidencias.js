import { useState } from 'react';
import { postIncidencia } from '../../data/api/incidenciasApi';

export const useIncidencias = () => {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSuccess, setIsSuccess] = useState(false);
  const [errorApi, setErrorApi] = useState(null);

  const submitIncidencia = async (incidenciaData) => {
    try {
      setIsSubmitting(true);
      setErrorApi(null);
      
      // La API wrapper lanzará excepción si no es status 2xx
      await postIncidencia(incidenciaData);
      
      setIsSuccess(true);
      return { success: true };
    } catch (err) {
      setErrorApi(err.message || 'No se pudo conectar al servidor.');
      return { success: false, error: err.message };
    } finally {
      setIsSubmitting(false);
    }
  };

  const resetState = () => {
    setIsSuccess(false);
    setErrorApi(null);
  };

  return {
    submitIncidencia,
    isSubmitting,
    isSuccess,
    errorApi,
    resetState,
  };
};
