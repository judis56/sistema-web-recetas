/// JavaScript Document sia 

var app = angular.module('myApp', []);
app.controller('controlador', function($scope, $sce, $http, $window) {

  $scope.cerrar = function(ventana) {
    var modalEl = document.getElementById(ventana);
    var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    modal.hide();

    $scope.usuario = "";
    $scope.clave = "";
  };

  $scope.cargarPanelAdmin = function() {
    $http.get("php/recetas.php").then(function(response) {
      $scope.recetas = response.data;
    });

    $http.get("php/usuarios.php").then(function(response) {
      $scope.usuarios = response.data;
    });
  };

  $scope.nuevaReceta = {};

  $scope.agregarReceta = function() {
    $http.post("php/agregar_receta.php", $scope.nuevaReceta).then(function(response) {
      alert("Receta guardada correctamente");
      $scope.nuevaReceta = {};
      $scope.cargarPanelAdmin();
    });
  };

  $scope.eliminarReceta = function(id) {
    $http.get("php/eliminar_receta.php?id=" + id).then(function(response) {
      alert("Receta eliminada");
      $scope.cargarPanelAdmin();
    });
  };

  $scope.eliminarUsuario = function(correo) {
    $http.get("php/eliminar_usuario.php?correo=" + correo).then(function(response) {
      alert("Usuario eliminado");
      $scope.cargarPanelAdmin();
    });
  };

  // LOGIN
  $scope.login = function() {
    var config = { async: false };

    $http.post("php/login.php", {
      usuario: $scope.usuario,
      clave: $scope.clave
    }, config).then(function(response) {
      if (response.data == 0) {
        alert("Error. Usuario no encontrado");
      } else {
        angular.forEach(response.data.registro, function(value, key) {
          $scope.nombre = value.nombre;
          $scope.ap = value.ap;
          $scope.am = value.am;
          $scope.usuario = value.usuario;
          $scope.clave = value.clave;
          $scope.rol = value.rol;
        });

        if ($scope.rol === "admin") {
          $scope.cargarPanelAdmin();
          var adminModalEl = document.getElementById('adminModal');
          var adminModal = new bootstrap.Modal(adminModalEl);
          adminModal.show();
        } else {
          // 👇 Mostrar recetas al usuario normal
          $scope.cargarRecetasPublicas();

          var successModalEl = document.getElementById('successModal');
          var successModal = new bootstrap.Modal(successModalEl);
          successModal.show();
        }
      }
    });
  };

  $scope.registrar = function() {
    var config = { async: false };
    $http.post("php/Registro.php", {
      nombre: $scope.nombre,
      ap: $scope.ap,
      am: $scope.am,
      correo: $scope.correo,
      celular: $scope.celular
    }, config).then(function(response) {
      if (response.data == 0) {
        alert("Error, el correo ya existe.");
      } else {
        alert("Usuario registrado correctamente.");
        $scope.limpiarRegistro();
      }
    });
  };

  $scope.limpiarRegistro = function() {
    $scope.nombre = "";
    $scope.ap = "";
    $scope.am = "";
    $scope.correo = "";
    $scope.celular = "";
  };

  $scope.recetasPublicas = [];

  $scope.cargarRecetasPublicas = function() {
    $http.get("php/recetas.php").then(function(response) {
      $scope.recetasPublicas = response.data;
    });
  };

  $scope.verReceta = function(receta) {
    $scope.recetaSeleccionada = receta;
    var modalEl = document.getElementById('modalReceta');
    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  };

  $scope.guardarFavorito = function(idReceta) {
    if (!$scope.usuario || $scope.usuario === "") {
      alert("Debes iniciar sesión para guardar favoritos.");
      return;
    }

    console.log("Guardando favorito para la receta con ID:", idReceta);

    $http.post("php/guardar_favorito.php", {
      nombre: $scope.usuario,
      id_receta: idReceta
    }).then(function(response) {
      console.log("Respuesta del servidor:", response.data);
      if (response.data.status === "ok") {
        alert("Guardado en favoritos");
      } else if (response.data.status === "existe") {
        alert("Ya está en tus favoritos");
      } else {
        alert("Error al guardar favorito: " + response.data.mensaje);
      }
    }, function(error) {
      console.error("Error al conectar con el servidor", error);
      alert("Error al guardar favorito");
    });
  };

  $scope.enviarCorreo = function() {
    $http.post("php/enviar_correo.php", {
      nombre: $scope.nombre,
      correo: $scope.correo
    }).then(function(response) {
      if (response.data.status === "ok") {
        alert("📬 Correo enviado correctamente");
      } else {
        alert("❌ Error: " + response.data.mensaje);
      }
    });
  };

});
//fav
// Código AngularJS dentro del controlador
$scope.obtenerFavoritos = function () {
  if (!$scope.usuario || $scope.usuario === "") {
    alert("Debes iniciar sesión para ver tus favoritos.");
    return;
  }

  console.log("Usuario autenticado:", $scope.usuario);

  $http.post("php/obtener_favoritos.php", {
    usuario: $scope.usuario
  }).then(function (response) {
    console.log("Respuesta de favoritos:", response.data);
    if (response.data && response.data.length > 0) {
      $scope.favoritos = response.data;
      $scope.mostrarModalFavoritos();
    } else {
      alert("No tienes recetas favoritas.");
    }
  }, function (error) {
    console.error("Error al obtener favoritos:", error);
    alert("Hubo un error al cargar los favoritos.");
  });
};

$scope.mostrarModalFavoritos = function () {
  var modalEl = document.getElementById('modalFavoritos');
  var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();
};