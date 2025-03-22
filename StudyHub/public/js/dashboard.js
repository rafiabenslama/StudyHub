var likedAnnonces = new Set();  
var currentLocationFilter = null; 

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13.5,
        center: {lat: 43.710000, lng: 7.260000}
    });

    var locations = {
        'Campus Trotabas': {lat: 43.69619337687686, lng: 7.24549603749049},
        'Campus Valrose': {lat: 43.71596147663534, lng: 7.266275333800547},
        'Campus Pasteur': {lat: 43.72569249660889, lng: 7.2802502970085134},
        'Campus Carlone': {lat: 43.69273193426065, lng: 7.236917581665765},
        'Campus St Jean d\'Angély': {lat: 43.709241485453795, lng: 7.28801600828087}
    };

    var normalIcon = {
        url: '/public/images/uni-logo.png',
        scaledSize: new google.maps.Size(60, 60),  
        origin: new google.maps.Point(0, 0), 
        anchor: new google.maps.Point(30, 30) 
    };

    var hoverIcon = {
        url: '/public/images/uni-logo.png',
        scaledSize: new google.maps.Size(70, 70), 
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(35, 35)
    };

    Object.keys(locations).forEach(function(key) {
        var marker = new google.maps.Marker({
            position: locations[key],
            map: map,
            title: key,
            icon: normalIcon
        });

        marker.addListener('mouseover', function() {
            marker.setIcon(hoverIcon);
        });

        marker.addListener('mouseout', function() {
            marker.setIcon(normalIcon);
        });

        marker.addListener('click', function() {
            scrollToAnnonces();
            fetchAnnoncesByLocation(key);
        });
    });
}

function fetchAnnonces() {
    var url = '/includes/annonces/fetch_announcements.php';
    var data = {};

    if (currentLocationFilter) {
        data.lieu = currentLocationFilter;
    }

    $.ajax({
        url: url,
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(data) {
            displayAnnonces(data); 
        },
        error: function() {
            console.log('Erreur lors de la récupération des annonces');
        }
    });
}

function displayAnnonces(annonces) {
    $('#annonces').empty();
    annonces.forEach(function(annonce) {
        var annonceDiv = $('<div>').addClass('annonce');
        var likeButton = $('<button>')
            .text('Like')
            .addClass('like-button')
            .css('background-color', likedAnnonces.has(annonce.id) ? '#d2b4f8' : '#9c5eec')
            .on('click', function() {
                likeAnnonce(annonce.id, $(this));
            });
        annonceDiv.append(likeButton);
        annonceDiv.append(
            '<h3>' + $('<div>').text(annonce.titre).html() + '</h3>' +
            '<p class="utilisateur">@' + $('<div>').text(annonce.utilisateur).html() + '</p>' +
            '<p class="categorie">' + $('<div>').text(annonce.categorie).html() + '</p>' +
            '<p><strong>Adresse :</strong> ' + $('<div>').text(annonce.lieu).html() + '</p>' +
            '<p><strong>Date :</strong> ' + $('<div>').text(annonce.date).html() + '</p>' +
            '<p><strong>Description :</strong>' + $('<div>').text(annonce.description).html() + '</p>'
        );
        
        $('#annonces').append(annonceDiv);
    });
}

function likeAnnonce(annonceId, buttonElement) {
    $.ajax({
        type: "POST",
        url: "/includes/annonces/like_announcement.php",
        data: { annonce_id: annonceId },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.liked) {
                likedAnnonces.add(annonceId); 
            } else {
                likedAnnonces.delete(annonceId); 

            }
        },
        error: function() {
            console.error("Erreur lors de l'ajout du like");
        }
    });
}

function fetchUserAnnouncements(userId) {
    $.ajax({
        url: `/includes/annonces/fetch_user_announcements.php?user_id=${userId}`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                console.error('Erreur:', data.error);
            } else {
                updateProfilePosts(data);
            }
        },
        error: function() {
            console.error('Erreur AJAX lors de la récupération des annonces');
        }
    });
}

function fetchConnectedUsers() {
    $.ajax({
        url: '/includes/users/fetch_connected_users.php', 
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var userList = $('#user-list'); 
            userList.empty();
            data.forEach(function(user) {
                var fullName = user.prenom + " " + user.nom; 
                var userElement = $('<li>').text(fullName).addClass('user');
                userElement.on('click', function() {
                    openUserProfile(user); 
                    fetchUserAnnouncements(user.id); 
                });
                userList.append(userElement);
            });
        },
        error: function() {
            console.error('Erreur lors de la récupération des utilisateurs connectés');
        }
    });
}

function openUserProfile(user) {
    $('#messageBtn').hide();
    $('#profileModal .profile-header h1').text(user.prenom + ' ' + user.nom);
    $('#profileModal .profile-header p').text('@' + user.id);
    if (user.id !== userId1) {
        $('#messageBtn').show();
        $('#messageBtn').off('click').on('click', function() {
            window.location.href = `/public/chat.php?user_id=${user.id}`;
        });
    } 
    $('#profileModal').show(); 
    fetchUserLikes(user.id);
}

function fetchUserProfile() {
    $.ajax({
        url: '/includes/users/fetch_user_profile.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (!data.error) {
                updateProfileModal(data);
                fetchUserAnnouncements(data.id); 
                fetchUserLikes(data.id); 
                profileModal.style.display = "block";
                $('#messageBtn').hide();
            } else {
                console.error('Erreur:', data.error);
            }
        },
        error: function() {
            console.error('Erreur AJAX lors de la récupération des données de l\'utilisateur');
        }
    });
}

function fetchUserLikes(userId) {
    $.ajax({
        url: `/includes/users/fetch_user_likes.php?user_id=${userId}`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var likesContainer = $('#publies');
            likesContainer.empty();
            if (data && data.length > 0) {
                data.forEach(function(annonce) {
                    likesContainer.append(
                        `<div class="post">
                            <h2>@${annonce.utilisateur}</h2>
                            <h3>${annonce.titre}</h3>
                            <p>${annonce.lieu}</p>
                            <p>${annonce.categorie}</p>
                            <p>${annonce.description}</p>
                        </div>`
                    );
                });
            } else {
                likesContainer.append('<p>Aucune publication aimée.</p>');
            }
        },
        error: function() {
            console.error('Erreur lors de la récupération des publications aimées');
        }
    });
}

function updateProfileModal(user) {
    document.querySelector('#profileModal .profile-header h1').textContent = user.prenom + ' ' + user.nom;
    document.querySelector('#profileModal .profile-header p').textContent = '@' + user.id;
}

var annonceModal = document.getElementById("annonceModal");
var openModalBtn = document.getElementById("openModal");
var closeAnnonce = document.getElementById("closeAnnonce");

var profileModal = document.getElementById("profileModal");
var profileBtn = document.getElementById("profileBtn");
var closeProfile = document.getElementById("closeProfile");

if (openModalBtn && annonceModal && closeAnnonce && profileModal && profileBtn && closeProfile) {
    openModalBtn.onclick = function(event) {
        event.stopPropagation();
        annonceModal.style.display = "block";
    };

    closeAnnonce.onclick = function(event) {
        event.stopPropagation();
        annonceModal.style.display = "none";
    };

    profileBtn.onclick = function(event) {
        event.stopPropagation();
        fetchUserProfile();
    };

    closeProfile.onclick = function(event) {
        event.stopPropagation();
        profileModal.style.display = "none";
    };

    window.onclick = function(event) {
        if (!event.target.closest(".modal-content")) {
            if (annonceModal.style.display === "block" && event.target === annonceModal) {
                annonceModal.style.display = "none";
            }
            if (profileModal.style.display === "block" && event.target === profileModal) {
                profileModal.style.display = "none";
            }
        }
    };
} else {
    console.error("Some elements are missing on the page.");
}

function changeTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tab");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function closeProfile() {
    console.log("Profil fermé.");
}

function updateProfilePosts(annonces) {
    var postsContainer = $('#likes');  
    postsContainer.empty();
    annonces.forEach(function(annonce) {
        postsContainer.append(
            `<div class="post">
                <h3>${annonce.titre}</h3>
                <p>${annonce.description}</p>
                <p><strong>Catégorie:</strong> ${annonce.categorie}</p>
                <p><strong>Date:</strong> ${annonce.date}</p>
                <p><strong>Lieu:</strong> ${annonce.lieu}</p>
            </div>`
        );
    });
}

$(document).ready(function(){
    $('#ajoutAnnonceForm').on('submit', function(e){
        e.preventDefault(); 

        $.ajax({
            type: "POST",
            url: "/includes/annonces/add_announcement.php", 
            data: $(this).serialize(), 
            success: function(response){
                $('#result').html(response.result).show();
                $('#annonceModal').hide(); 
                $('#ajoutAnnonceForm')[0].reset();
                setTimeout(function() {
                    $('#result').fadeOut('slow'); 
                }, 1500); 
            },
            error: function(){
                $('#result').html("<p>Une erreur est survenue lors de l'ajout de l'annonce.</p>");
            }
        });
    });
    setInterval(fetchAnnonces, 2000); 
    setInterval(fetchConnectedUsers, 1000);
    setInterval(checkForNewMessages, 3000); 
});

document.addEventListener('DOMContentLoaded', function() {
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('date').setAttribute('min', today);
});

function checkForNewMessages() {
    $.ajax({
        url: '/includes/chat/check_messages.php', 
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.newMessage) {
                displayNotification(response.sender, response.message);
            }
        },
        error: function() {
            console.error("Erreur lors de la récupération des messages");
        }
    });
}

function displayNotification(sender, message) {
    var notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerHTML = `Message reçu de @${sender}: "${message}"`;

    var container = document.getElementById('notification-container');
    container.appendChild(notification);

    notification.style.right = '310px'; 

    setTimeout(() => {
        notification.style.right = '0px'; 
        setTimeout(() => {
            container.removeChild(notification); 
        }, 500); 
    }, 3000); 
}

function scrollToAnnonces() {
    document.getElementById('annonces').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function fetchAnnoncesByLocation(location) {
    currentLocationFilter = location; 
    fetchAnnonces(); 
}

function resetLocationFilter() {
    currentLocationFilter = null; 
    fetchAnnonces(); 
    scrollToAnnonces();
}

document.getElementById('resetFilterBtn').addEventListener('click', resetLocationFilter);
