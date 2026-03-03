import React from "react";
import { View, Text, Image, TouchableOpacity, StyleSheet } from "react-native";
import { createBottomTabNavigator } from "@react-navigation/bottom-tabs";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";

// Importación de las pantallas principales
import DashboardScreen from "../screens/DashboardScreen";
import AuditoriasScreen from "../screens/AuditoriasScreen";
import PerfilScreen from "../screens/PerfilScreen";

const Tab = createBottomTabNavigator();

export default function TabNavigator() {
  return (
    <Tab.Navigator
      screenOptions={({ route, navigation }) => ({
        // --- CONFIGURACIÓN DE LOS ÍCONOS DEL MENÚ INFERIOR ---
        tabBarIcon: ({ color, size }) => {
          let iconName;
          if (route.name === "Inicio") iconName = "home";
          else if (route.name === "Auditorías") iconName = "inbox";
          else if (route.name === "Perfil") iconName = "user";

          return <Feather name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: colors.accent,
        tabBarInactiveTintColor: colors.textSecondary,
        tabBarStyle: {
          backgroundColor: colors.surface,
          borderTopWidth: 1,
          borderTopColor: "#e2e8f0",
          paddingBottom: 15,
          paddingTop: 8,
          height: 80,
        },
        tabBarLabelStyle: {
          fontSize: 12,
          fontWeight: "700",
        },

        // --- CONFIGURACIÓN DEL HEADER GLOBAL UNIFICADO ---
        headerStyle: {
          backgroundColor: colors.primary,
          height: 110, // Altura cómoda para el header
          elevation: 0, // Quita la sombra en Android para un diseño más plano
          shadowOpacity: 0, // Quita la sombra en iOS
        },
        headerTitle: "", // Ocultamos el título por defecto

        // Lado Izquierdo: Logo, Título y Subtítulo dinámico
        headerLeft: () => (
          <View style={styles.headerLeft}>
            <Image
              source={require("../../assets/logo.png")}
              style={styles.headerLogo}
              resizeMode="contain"
            />
            <View>
              <Text style={styles.headerTitle}>S.G.A.F.A QR</Text>
              <Text style={styles.headerSubtitle}>
                {route.name.toUpperCase()}
              </Text>
            </View>
          </View>
        ),

        // Lado Derecho: Botón de Sincronización Global
        headerRight: () => (
          <TouchableOpacity
            onPress={() => navigation.navigate("Sincronizacion")}
            style={styles.headerRight}
          >
            <Feather name="refresh-cw" size={24} color={colors.surface} />
          </TouchableOpacity>
        ),
      })}
    >
      <Tab.Screen name="Inicio" component={DashboardScreen} />
      <Tab.Screen name="Auditorías" component={AuditoriasScreen} />
      <Tab.Screen name="Perfil" component={PerfilScreen} />
    </Tab.Navigator>
  );
}

const styles = StyleSheet.create({
  headerLeft: {
    flexDirection: "row",
    alignItems: "center",
    marginLeft: 24,
  },
  headerLogo: {
    width: 40,
    height: 40,
    marginRight: 12,
  },
  headerTitle: {
    color: colors.surface,
    fontSize: 18,
    fontWeight: "800",
    letterSpacing: 0.5,
  },
  headerSubtitle: {
    color: "#94a3b8",
    fontSize: 12,
    fontWeight: "700",
    marginTop: 2,
    letterSpacing: 1,
  },
  headerRight: {
    marginRight: 24,
    padding: 4,
  },
});
