$(document).ready(function() {
    $('#testimonial-form').submit(function(event) {
        event.preventDefault();
        
        var name = $('#name').val();
        var comment = $('#comment').val();
        var rating = $('#rating').val();

        $.ajax({
            type: 'POST',
            url: 'add_testimonial.php',
            data: {
                name: name,
                comment: comment,
                rating: rating
            },
            success: function(response) {
                $('#testimonial-list').append('<div><p><strong>' + name + '</strong> (' + rating + ' étoiles): ' + comment + '</p></div>');
                $('#name').val('');
                $('#comment').val('');
                $('#rating').val('');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });


$('#contact-form input').on('input', function() {
        var isValid = true;
        $('#contact-form input').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                return false; // Sortir de la boucle si un champ est vide
            }
        });
        if (isValid) {
            $('#submit-btn').prop('disabled', false);
        } else {
            $('#submit-btn').prop('disabled', true);
        }
    });
});

$(document).ready(function() {
    // Gestion de la soumission du formulaire de témoignages
    $('#testimonial-form').submit(function(event) {
        event.preventDefault();
        
        var name = $('#name').val();
        var comment = $('#comment').val();
        var rating = $('#rating').val();

        $.ajax({
            type: 'POST',
            url: 'add_testimonial.php',
            data: {
                name: name,
                comment: comment,
                rating: rating
            },
            success: function(response) {
                $('#testimonial-list').append('<div><p><strong>' + name + '</strong> (' + rating + ' étoiles): ' + comment + '</p></div>');
                $('#name').val('');
                $('#comment').val('');
                $('#rating').val('');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Validation du formulaire de contact
    $('#contact-form input').on('input', function() {
        var isValid = true;
        $('#contact-form input').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                return false; // Sortir de la boucle si un champ est vide
            }
        });
        $('#submit-btn').prop('disabled', !isValid);
    });

    // Fonction pour filtrer les véhicules
    function filtrerVehicules() {
        var kilometrageMax = $('#kilometrage_max').val();
        var prixMax = $('#prix_max').val();
        var anneeMin = $('#annee_min').val();
    
        $.ajax({
            url: 'filtrage_vehicules.php',
            type: 'GET',
            data: {
                kilometrage_max: kilometrageMax,
                prix_max: prixMax,
                annee_min: anneeMin
            },
            success: function(data) {
                $('.row').html(data);
            }
        });
    }
    
    // Écouteurs d'événements pour les changements de curseurs
    $('#kilometrage_max, #prix_max, #annee_min').on('input', filtrerVehicules);

    // Fonction pour réinitialiser les curseurs et la liste des véhicules
    $('#reset-filters').on('click', function() {
        $('#kilometrage_max').val('').change();
        $('#prix_max').val('').change();
        $('#annee_min').val('').change();
    });

    // Appel initial pour charger les véhicules
    filtrerVehicules();
});

$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault(); // Empêche le formulaire de soumettre normalement

        $.ajax({
            type: "POST",
            url: "envoyer_message.php",
            data: $(this).serialize(),
            success: function(response) {
                // Affichez votre message de succès ici
                $('#messageSuccess').show();
            }
        });
    });
});
document.getElementById('formTemoignage').addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche la soumission traditionnelle du formulaire

    var formData = new FormData(this);

    // Ici, ajoutez votre appel AJAX pour soumettre le formulaire
    // Pour cet exemple, nous allons simplement afficher un message de confirmation
    document.getElementById('messageConfirmation').textContent = 'Merci pour votre témoignage !';

    // Réinitialiser le formulaire après la soumission
    this.reset();
});
document.querySelectorAll('.service-item').forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault();

    // Récupérer les données
    const serviceName = this.getAttribute('data-name');
    const serviceDescription = this.getAttribute('data-description');
    const serviceImage = this.getAttribute('data-image');

    // Remplir la modal avec les données
    document.getElementById('serviceName').textContent = serviceName;
    document.getElementById('serviceDescription').textContent = serviceDescription;
    document.getElementById('serviceImage').src = serviceImage;
    document.getElementById('serviceImage').alt = 'Image de ' + serviceName;

    // Afficher la modal
    $('#serviceDetailModal').modal('show');
  });
});
// Script pour charger les détails du service via AJAX et les afficher dans la modal
$(document).ready(function() {
    $('.service-link').click(function(e) {
        e.preventDefault();
        var slug = $(this).data('slug');
        // Requête AJAX pour récupérer les détails du service
        $.get('fetch-service-details.php', { slug: slug }, function(data) {
            $('#serviceModal .modal-body').html(data);
            $('#serviceModal').modal('show');
        });
    });
});

// Script page service
document.addEventListener('DOMContentLoaded', (event) => {
    const serviceImage = document.querySelector('img');
    if (serviceImage) {
        serviceImage.addEventListener('click', () => {
            alert('Vous avez cliqué sur l\'image du service.');
        });
    }
});