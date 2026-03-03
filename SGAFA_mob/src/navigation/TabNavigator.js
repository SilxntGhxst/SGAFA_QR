import React from 'react';
import { View, Text, Image, StyleSheet } from 'react-native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Feather } from '@expo/vector-icons'; // Importamos la librería de íconos
import DashboardScreen from '../screens/DashboardScreen';
import AuditoriasScreen from '../screens/AuditoriasScreen';
import PerfilScreen from '../screens/PerfilScreen';
import { colors } from '../theme/colors';

const Tab = createBottomTabNavigator();

// --- COMPONENTE QUIRÚRGICO: Header Personalizado ---
const CustomHeader = ({ title }) => (
  <View style={styles.headerContainer}>
    <Image 
      source={require('../../assets/logo.png')} 
      style={styles.headerLogo} 
      resizeMode="contain" 
    />
    <View style={styles.headerTextContainer}>
      <Text style={styles.headerTitle}>S.G.A.F.A QR</Text>
      <Text style={styles.headerSubtitle}>{title}</Text>
    </View>
  </View>
);

export default function TabNavigator() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        // Inyectamos nuestro header global
        header: () => <CustomHeader title={route.name} />,
        
        // Asignamos íconos dinámicamente según la pestaña
        tabBarIcon: ({ color, size }) => {
          let iconName;
          if (route.name === 'Inicio') iconName = 'home';
          else if (route.name === 'Auditorías') iconName = 'clipboard';
          else if (route.name === 'Perfil') iconName = 'user';
          
          return <Feather name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: colors.accent,
        tabBarInactiveTintColor: colors.textSecondary,
        tabBarStyle: {
          backgroundColor: colors.surface,
          borderTopWidth: 1,
          borderTopColor: '#e2e8f0',
          height: 75,
          paddingBottom: 10,
          paddingTop: 8,
        },
        tabBarLabelStyle: {
          fontSize: 12,
          fontWeight: '600',
        }
      })}
    >
      <Tab.Screen name="Inicio" component={DashboardScreen} />
      <Tab.Screen name="Auditorías" component={AuditoriasScreen} />
      <Tab.Screen name="Perfil" component={PerfilScreen} />
    </Tab.Navigator>
  );
}

const styles = StyleSheet.create({
  headerContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.primary, // Fondo oscuro corporativo
    paddingTop: 48, // Espacio para la barra de estado del celular
    paddingBottom: 16,
    paddingHorizontal: 24,
  },
  headerLogo: {
    width: 44,
    height: 44,
    marginRight: 12,
  },
  headerTextContainer: {
    justifyContent: 'center',
  },
  headerTitle: {
    color: colors.surface,
    fontSize: 18,
    fontWeight: '800',
    letterSpacing: 0.5,
  },
  headerSubtitle: {
    color: '#94a3b8', // Azul grisáceo para diferenciar el subtítulo
    fontSize: 13,
    fontWeight: '600',
    marginTop: 2,
    textTransform: 'uppercase',
  }
});