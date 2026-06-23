<!-- Ligne Narthex (séparateur) -->
@if($double ?? false)
    <!-- Double ligne : 1px gris + 4px rouge -->
    <div class="border-t border-border-light">
        <div class="border-t-4 border-cardinal"></div>
    </div>
@else
    <!-- Simple : 4px rouge pleine largeur -->
    <div class="border-t-4 border-cardinal w-full"></div>
@endif
