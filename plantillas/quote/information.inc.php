<?php
// The standalone Information page was replaced by an in-page drawer on the quote edit
// page. Old bookmarks/audit-trail links still point here, so redirect them straight
// to the quote page with the drawer auto-opened on the Information tab. redirigir1 (not
// redirigir) because vistas/perfil.php already echoed the page shell before reaching
// this case, so a header()-based redirect would silently fail.
Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $id_rfq . '?drawer=information');
