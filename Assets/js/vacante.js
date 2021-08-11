
const formVacancy = document.querySelector('#form-vacancy');
const bntSubmit = document.getElementById('btn_submit');

/*  RECEPCION DE VALOR DEL ELEMENTO DEFINIDO btn_submit, previniendo el evento por defecto en
caso de ser este btn clicado y ejecutanfdo el metodo validateFormUser*/

    bntSubmit.addEventListener('click', e => {
        e.preventDefault();

        validateFormVacancy();
        // insertVacancy();
    });

    const insertVacancy = async () => {
        //enviar los datos mediante una petición fetch
        tinyMCE.triggerSave();
        let formData = new FormData(formVacancy);
        
        const url = 'http://localhost/PonsLabor/Vacante/setVacante';

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: formData
            })
            const { statusUser, msg } = await res.json();

            if (statusUser ) {
                swal("Vacante", msg, "success");              
            }
            else {
                swal("Error", msg, "error");//mostrar la alerta
            }
        } 
        catch (error) {
            swal("Error", error, "error");
        }
    }

    const validateFormVacancy = () => {
        tinyMCE.triggerSave();
        const id = document.querySelector('#idVacancy').value;
        const nombre = document.querySelector('#nombre').value;
        const cantidad = document.querySelector('#cantidad').value;
        const especificaciones = document.querySelector('#especificaciones').value;
        const perfil = document.querySelector('#perfil').value;
        const tipoContrato = document.querySelector('#tipoContrato').value;
        const sueldo = document.querySelector('#sueldo').value;
        const fechapublicacion = document.querySelector('#fechapublicacion').value;
        const fechacierre = document.querySelector('#fechacierre').value;
        const direccion = document.querySelector('#direccion').value;
        const estado = document.querySelector('#estado').value;

        if (nombre === '' || cantidad === '' || especificaciones === '' || perfil === '' || tipoContrato === ''
        || sueldo === '' || fechapublicacion === '' || fechacierre === '' || direccion === '' || estado === '') {
            swal(
                'Ha ocurrido un error',
                'Todos los campos son obligatorios.',
                'error'
            )
            return false;
        }
        else {
            insertVacancy();
        }
    }