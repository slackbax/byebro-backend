<?php

extract($_GET);

if (!isset($section) || $section == 'home'):
    include 'main/main-index.php';

// Cotizaciones
elseif ($section == 'quotations' and $_login):
    if ($sbs == 'createquotation'):
        include 'quotations/create-quotation.php';
    elseif ($sbs == 'managequotations'):
        include 'quotations/manage-quotations.php';
    else:
        include 'src/error.php';
    endif;

// Viajes
elseif ($section == 'trips' and $_login):
    if ($sbs == 'createtrip' and $_admin):
        include 'trips/create-trip.php';
    elseif ($sbs == 'managetrips'):
        include 'trips/manage-trips.php';
    elseif ($sbs == 'managequotas'):
        include 'trips/manage-quotas.php';
    elseif ($sbs == 'assignquota'):
        include 'trips/assign-quota.php';
    else:
        include 'src/error.php';
    endif;

// Participantes
elseif ($section == 'participants' and $_login):
    if ($sbs == 'createparticipant' and $_admin):
        include 'participants/create-participant.php';
    elseif ($sbs == 'manageparticipants'):
        include 'participants/manage-participants.php';
    else:
        include 'src/error.php';
    endif;

// Finanzas
elseif ($section == 'finance' and $_login):
    if ($sbs == 'manageoc' and $_admin):
        include 'finance/manage-oc.php';
    else:
        include 'src/error.php';
    endif;

// User menu
elseif ($section == 'adminusers' and $_login):
    if ($sbs == 'editprofile'):
        include 'admin/users/edit-profile.php';
    elseif ($sbs == 'changepass'):
        include 'admin/users/change-password.php';
    else:
        include 'src/error.php';
    endif;

// Users
elseif ($section == 'users' and ($_admin)):
    if ($sbs == 'createuser'):
        include 'admin/users/create-user.php';
    elseif ($sbs == 'manageusers'):
        include 'admin/users/manage-users.php';
    elseif ($sbs == 'edituser'):
        include 'admin/users/edit-user.php';
    else:
        include 'src/error.php';
    endif;

// Personal
elseif ($section == 'personal' and ($_admin)):
    // Cargo
    if ($sbs == 'createposition'):
        include 'admin/position/create-position.php';
    elseif ($sbs == 'managepositions'):
        include 'admin/position/manage-positions.php';
    elseif ($sbs == 'editposition'):
        include 'admin/position/edit-position.php';

    // Staff
    elseif ($sbs == 'createpersonal'):
        include 'admin/staff/create-personal.php';
    elseif ($sbs == 'managepersonal'):
        include 'admin/staff/manage-personal.php';
    elseif ($sbs == 'editpersonal'):
        include 'admin/staff/edit-personal.php';
    else:
        include 'src/error.php';
    endif;

// Ciudades
elseif ($section == 'cities' and ($_admin)):
    if ($sbs == 'createocity'):
        include 'admin/cities/create-ocity.php';
    elseif ($sbs == 'manageocities'):
        include 'admin/cities/manage-ocities.php';
    elseif ($sbs == 'createdcity'):
        include 'admin/cities/create-dcity.php';
    elseif ($sbs == 'managedcities'):
        include 'admin/cities/manage-dcities.php';
    else:
        include 'src/error.php';
    endif;

// Detalles de viaje
elseif ($section == 'tripdetails' and ($_admin)):
    // Alojamientos
    if ($sbs == 'createaccomodation'):
        include 'admin/accomodations/create-accomodation.php';
    elseif ($sbs == 'manageaccomodations'):
        include 'admin/accomodations/manage-accomodations.php';
    elseif ($sbs == 'editaccomodation'):
        include 'admin/accomodations/edit-accomodation.php';

    // Comida
    elseif ($sbs == 'createfood'):
        include 'admin/food/create-food.php';
    elseif ($sbs == 'managefood'):
        include 'admin/food/manage-food.php';
    elseif ($sbs == 'editfood'):
        include 'admin/food/edit-food.php';

    // Adicionales
    elseif ($sbs == 'createextra'):
        include 'admin/extras/create-extra.php';
    elseif ($sbs == 'manageextras'):
        include 'admin/extras/manage-extras.php';
    elseif ($sbs == 'editextra'):
        include 'admin/extras/edit-extra.php';

    else:
        include 'src/error.php';
    endif;

elseif ($section == 'forgotpass'):
    include 'admin/users/retrieve-password.php';
else:
    include 'src/error.php';
endif;
