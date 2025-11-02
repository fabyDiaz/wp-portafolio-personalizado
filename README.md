# Portfolio Personalizado - Plugin de WordPress

![Versión](https://img.shields.io/badge/version-2.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)
![Licencia](https://img.shields.io/badge/license-GPL--2.0-green.svg)

Plugin profesional para crear y mostrar un portfolio personalizado en WordPress con opciones completas de customización.

## 🚀 Características

- ✅ Custom Post Type "Proyectos" con taxonomía de tecnologías
- 🎨 Personalización completa de colores desde el panel de administración
- 🏷️ Sistema de etiquetas personalizables con colores individuales
- 📱 Diseño completamente responsive (móvil, tablet, desktop)
- ⚡ Shortcode flexible con múltiples opciones
- 🖼️ Soporte para imágenes destacadas e íconos Font Awesome
- 🔗 Enlaces personalizados (GitHub, Demo, Descarga)
- 🌐 Compatible con cualquier tema de WordPress (Divi, Elementor, Gutenberg, etc.)
- 🎭 Efectos visuales modernos y animaciones suaves

## 📋 Requisitos

- WordPress 5.0 o superior
- PHP 7.2 o superior
- MySQL 5.6 o superior

## 📦 Instalación

### Opción 1: Instalación Manual

1. Descarga el archivo ZIP del plugin
2. Ve a **WordPress Admin → Plugins → Añadir nuevo**
3. Haz clic en **Subir plugin** y selecciona el archivo ZIP
4. Haz clic en **Instalar ahora**
5. Activa el plugin

### Opción 2: Instalación por FTP

1. Descarga y descomprime el archivo ZIP
2. Sube la carpeta `portfolio-personalizado` a `/wp-content/plugins/`
3. Activa el plugin desde el menú de Plugins en WordPress

## 🎨 Configuración

### Panel de Configuración

Después de activar el plugin, encontrarás una nueva opción en el menú lateral:

**Mis Proyectos → Configuración**

Aquí podrás personalizar:

#### Colores
- Color Primario
- Color Secundario
- Color de Acento
- Color de Fondo de Cards
- Color de Texto de Cards
- Color de Fondo de Imagen (Inicio)	
- Color de Fondo de Imagen (Final)
- Color de Botones
- Color de Botones (Hover)
- Color de Texto de Botones

#### Estilos
- Border Radius de Cards (en píxeles)
- Border Radius de Botones (en píxeles)

#### Etiquetas Personalizadas
Crea etiquetas con colores personalizados para tus tecnologías:
1. Ingresa el slug de la etiqueta (ej: `react`, `vue`, `laravel`)
2. Selecciona el color de fondo
3. Selecciona el color de texto
4. Haz clic en "Agregar Etiqueta"

## 📝 Uso

### Crear un Proyecto

1. Ve a **Mis Proyectos → Añadir Proyecto**
2. Completa la información:
   - Título del proyecto
   - Descripción
   - Imagen destacada
   - Extracto (resumen corto)
3. En **Detalles del Proyecto**, agrega:
   - URL del Repositorio (GitHub, GitLab, etc.)
   - URL de Demo
   - Icono de Font Awesome (opcional)
   - Tipo de enlace principal
4. Asigna tecnologías desde el panel lateral
5. Publica el proyecto

### Mostrar Proyectos con Shortcode

Usa el shortcode `[mostrar_proyectos]` en cualquier página o entrada:

#### Shortcode Básico
```
[mostrar_proyectos]
```

#### Shortcode con Parámetros
```
[mostrar_proyectos limit="6" columns="3" category="web"]
```

**Parámetros disponibles:**
- `limit`: Número de proyectos a mostrar (-1 para todos)
- `columns`: Número de columnas (2, 3 o 4)
- `category`: Slug de la categoría de tecnología

#### Ejemplos de Uso

**Mostrar últimos 6 proyectos en 3 columnas:**
```
[mostrar_proyectos limit="6" columns="3"]
```

**Mostrar solo proyectos de WordPress:**
```
[mostrar_proyectos category="wordpress"]
```

**Mostrar todos los proyectos en 4 columnas:**
```
[mostrar_proyectos columns="4"]
```

### Uso con Page Builders

#### Divi
1. Añade un módulo de **Código** o **Texto**
2. Pega el shortcode
3. Guarda y publica

#### Elementor
1. Arrastra el widget **Shortcode**
2. Pega el shortcode
3. Actualiza la página

#### Gutenberg
1. Añade un bloque **Shortcode**
2. Pega el shortcode
3. Publica

## 🎯 Compatibilidad

Este plugin es **universal** y funciona con:

- ✅ **Divi** (tema completo y Divi Builder)
- ✅ **Elementor** (Free y Pro)
- ✅ **Gutenberg** (editor nativo de WordPress)
- ✅ **Beaver Builder**
- ✅ **WPBakery**
- ✅ Cualquier otro tema de WordPress

**No necesitas ningún tema específico para usar este plugin.**

## 🛠️ Desarrollo y Testing

### Probar el Plugin Localmente

#### Opción 1: Local by Flywheel (Recomendado)
1. Descarga [Local by Flywheel](https://localwp.com/)
2. Crea un nuevo sitio de WordPress
3. Instala el plugin en `/app/public/wp-content/plugins/`

#### Opción 2: XAMPP/MAMP
1. Instala XAMPP o MAMP
2. Instala WordPress en localhost
3. Copia el plugin a `wp-content/plugins/`

#### Opción 3: Docker
```bash
docker-compose up -d
```

### Testing en Diferentes Temas
1. Instala el plugin en tu instalación local
2. Cambia entre diferentes temas
3. Verifica que el diseño se mantiene consistente

## 📂 Estructura de Archivos

```
portfolio-personalizado/
│
├── portfolio-personalizado.php  (archivo principal)
├── README.md
├── LICENSE.txt
│
└── assets/
    ├── style.css              (estilos frontend)
    ├── admin.css              (estilos admin)
    └── admin.js               (JavaScript admin)
```

## 🔄 Actualización

Para actualizar el plugin:

1. Desactiva el plugin actual
2. Elimina la carpeta del plugin
3. Sube la nueva versión
4. Reactiva el plugin

**Nota:** Tus configuraciones y proyectos se mantienen en la base de datos.

## 🤝 Contribuir

¿Quieres contribuir al proyecto? ¡Genial!

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 🐛 Reportar Bugs

Si encuentras un bug, por favor:

1. Abre un [Issue en GitHub](https://github.com/fabyDiaz/wp-portafolio-personalizado/issues)
2. Describe el problema detalladamente
3. Incluye pasos para reproducir el error
4. Indica tu versión de WordPress y PHP

## 📝 Changelog

### Version 2.0.0
- ✨ Panel de configuración completo
- 🎨 Personalización de colores
- 🏷️ Sistema de etiquetas personalizables
- 🌐 Compatibilidad universal con todos los temas
- 📱 Mejoras en responsive design

### Version 1.0.0
- 🎉 Lanzamiento inicial

## 📄 Licencia

Este plugin está licenciado bajo GPL v2 o posterior.

## 👤 Autor

**Fabiola Díaz**
- Website: [fabydev.cl](https://fabydev.cl)
- GitHub: [@fabyDiaz](https://github.com/fabyDiaz)

## 💖 Soporte

Si este plugin te ha sido útil, considera:
- ⭐ Darle una estrella en GitHub
- 🐛 Reportar bugs y sugerir mejoras
- 📣 Compartirlo con otros desarrolladores

---

**¿Necesitas ayuda?** Visita la [documentación completa](https://github.com/fabyDiaz/wp-portafolio-personalizado) o abre un issue en GitHub.