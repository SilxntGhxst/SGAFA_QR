import { useState, useCallback } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import { fetchDashboardData } from '../../data/api/dashboardApi';

export const useDashboard = () => {
  const [data, setData] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);

  const loadDashboard = useCallback(async () => {
    try {
      setIsLoading(true);
      setError(null);
      const result = await fetchDashboardData();
      setData(result);
    } catch (err) {
      setError(err.message || "Error al cargar los datos del dashboard");
    } finally {
      setIsLoading(false);
    }
  }, []);

  // Se recarga automáticamente cada que el usuario vuelve a ver la pantalla "Dashboard"
  useFocusEffect(
    useCallback(() => {
      loadDashboard();
    }, [loadDashboard])
  );

  return {
    data,
    isLoading,
    error,
    refetch: loadDashboard
  };
};
