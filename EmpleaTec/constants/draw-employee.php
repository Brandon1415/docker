<form name="frm" action="app/create-account.php" method="POST" autocomplete="off">
<div class="login-box-wrapper">
							
<div class="modal-header">
<h4 class="modal-title text-center text-white  text-transform: uppercase">Crea tu cuenta gratis</h4>
</div>

<div class="modal-body">
																
<div class="row gap-20">
											

												

												
<div class="col-sm-12 col-md-12 ">

<div class="form-group"> 
<label class= "text-white">Nombres</label>
<input class="form-control" placeholder="Ingresa tu Nombres" name="fname" required type="text" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios."> 
</div>
												
</div>

<div class="col-sm-12 col-md-12">

<div class="form-group"> 
<label class= "text-white">Apellidos</label>
<input class="form-control" placeholder="Ingresa tu Apellidos" name="lname" required type="text" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"title="Solo se permiten letras y espacios."> 
</div>
												
</div>
												
<div class="col-sm-12 col-md-12">

<div class="form-group"> 
<label class= "text-white">Correo Electrónico</label>
<input class="form-control" placeholder="Ingresa Correo Electrónico" name="email" required  type="email"  title="Ingresa un correo electrónico válido."> 
</div>
												
</div>
												
<div class="col-sm-12 col-md-12">
    <div class="form-group">
        <label class="text-white">Contraseña</label>
        <input class="form-control" placeholder="Min 8 y Max 20 caracteres" name="password" required type="password" id="password">
    </div>
</div>

<div class="col-sm-12 col-md-12">
    <div class="form-group">
        <label class="text-white">Confirmar contraseña</label>
        <input class="form-control" placeholder="Vuelve a escribir" name="confirmpassword" required type="password" id="confirmpassword">
    </div>
</div>

<div class="form-check col-md-12">
    <input type="checkbox" class="form-check-input" id="showPasswords">
    <label class="form-check-label text-white" for="showPasswords">Mostrar contraseñas</label>
</div>
<script>
document.getElementById('showPasswords').addEventListener('change', function() {
    var passwordField = document.getElementById('password');
    var confirmPasswordField = document.getElementById('confirmpassword');

    // Si el checkbox está marcado, mostramos las contraseñas
    if (this.checked) {
        passwordField.type = 'text';
        confirmPasswordField.type = 'text';
    } else {
        // Si el checkbox no está marcado, ocultamos las contraseñas
        passwordField.type = 'password';
        confirmPasswordField.type = 'password';
    }
});
</script>

												
<input type="hidden" name="acctype" value="101">
												
												
</div>

</div>

<div class="modal-footer text-center">
<button  onclick="return val();" type="submit" name="reg_mode" class="btn btn-primary">Registrar</button>
</div>
										
</div>
</form>