<form action="{$link->getPageLink('my-account')}" method="post">
    <input type="hidden" name="submit_preferences" value="1" />
    <div class="form-group">
        <label for="type_consommation">Type de consommation</label>
        <select name="type_consommation" class="form-control">
            <option value="sportif" {if $type_consommation == 'sportif'}selected{/if}>Sportif</option>
            <option value="gourmand" {if $type_consommation == 'gourmand'}selected{/if}>Gourmand</option>
            <option value="bio" {if $type_consommation == 'bio'}selected{/if}>Bio</option>
        </select>
    </div>

    <div class="form-group">
        <label for="souhaitez_cereales_originales">Souhaitez-vous des céréales originales ?</label>
        <select name="souhaitez_cereales_originales" class="form-control">
            <option value="oui" {if $souhaitez_cereales_originales == 'oui'}selected{/if}>Oui</option>
            <option value="non" {if $souhaitez_cereales_originales == 'non'}selected{/if}>Non</option>
        </select>
    </div>

    <div class="form-group">
        <label for="gout_prefere">Goût préféré</label>
        <select name="gout_prefere" class="form-control">
            <option value="chocolat" {if $gout_prefere == 'chocolat'}selected{/if}>Chocolat</option>
            <option value="nature" {if $gout_prefere == 'nature'}selected{/if}>Nature</option>
            <option value="sucree" {if $gout_prefere == 'sucree'}selected{/if}>Sucrée</option>
            <option value="miel" {if $gout_prefere == 'miel'}selected{/if}>Miel</option>
            <option value="caramel" {if $gout_prefere == 'caramel'}selected{/if}>Caramel</option>
            <option value="speculoos" {if $gout_prefere == 'speculoos'}selected{/if}>Spéculoos</option>
            <option value="fraise" {if $gout_prefere == 'fraise'}selected{/if}>Fraise</option>
        </select>
    </div>

    <div class="form-group">
        <label for="forme_favorite">Forme favorite</label>
        <select name="forme_favorite" class="form-control">
            <option value="boule" {if $forme_favorite == 'boule'}selected{/if}>Boule</option>
            <option value="triangle" {if $forme_favorite == 'triangle'}selected{/if}>Triangle</option>
            <option value="cube" {if $forme_favorite == 'cube'}selected{/if}>Cube</option>
            <option value="petale" {if $forme_favorite == 'petale'}selected{/if}>Pétale</option>
            <option value="donut" {if $forme_favorite == 'donut'}selected{/if}>Donut</option>
            <option value="etoile" {if $forme_favorite == 'etoile'}selected{/if}>Étoile</option>
        </select>
    </div>

    <div class="form-group">
        <label for="consommation_pour_qui">Consommation pour</label>
        <select name="consommation_pour_qui" class="form-control">
            <option value="personnel" {if $consommation_pour_qui == 'personnel'}selected{/if}>Personnel</option>
            <option value="enfants" {if $consommation_pour_qui == 'enfants'}selected{/if}>Enfants</option>
            <option value="les deux" {if $consommation_pour_qui == 'les deux'}selected{/if}>Les deux</option>
        </select>
    </div>

    <button type="submit" name="submit_preferences" class="btn btn-primary">Sauvegarder les préférences</button>
</form>

