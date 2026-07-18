{{-- Champs répétables + réordonnables du sommaire. Requiert dans le scope Alpine parent :
     `sommaire` (tableau d'objets {title, subtitle, page}) et `dragFrom` (index en cours de glisser, ou null). --}}
<div class="sommaire-builder">
    <template x-for="(item, i) in sommaire" :key="i">
        <div class="sommaire-row"
             style="display:grid;grid-template-columns:22px 1fr 1fr 60px 34px;gap:0.4rem;align-items:center;margin-bottom:0.45rem;transition:opacity .15s;"
             :style="dragFrom === i ? 'opacity:0.4' : ''"
             @dragover.prevent
             @drop.prevent="if (dragFrom !== null && dragFrom !== i) { const moved = sommaire.splice(dragFrom, 1)[0]; sommaire.splice(i, 0, moved); } dragFrom = null">
            <span class="sommaire-drag" draggable="true"
                  @dragstart="dragFrom = i" @dragend="dragFrom = null"
                  title="Glisser pour réordonner"
                  style="cursor:grab;color:var(--text-muted);font-size:1.1rem;line-height:1;text-align:center;user-select:none;">⠿</span>
            <input type="text" x-model="item.title" :name="`sommaire_items[${i}][title]`" placeholder="Titre (ex. Chapitre 1)" style="margin:0;">
            <input type="text" x-model="item.subtitle" :name="`sommaire_items[${i}][subtitle]`" placeholder="Sous-titre (facultatif)" style="margin:0;">
            <input type="number" min="1" x-model="item.page" :name="`sommaire_items[${i}][page]`" placeholder="Page" style="margin:0;">
            <button type="button" @click="sommaire.splice(i, 1)" title="Retirer cette entrée"
                    style="border:1px solid var(--border);background:#fff;color:var(--cardinal);border-radius:6px;height:38px;cursor:pointer;font-size:1.1rem;line-height:1;display:flex;align-items:center;justify-content:center;"
                    x-show="sommaire.length > 1">&times;</button>
        </div>
    </template>
    <button type="button" @click="sommaire.push({title:'',subtitle:'',page:''})" class="btn-ghost btn-xs" style="margin-top:0.2rem;">
        + Ajouter une entrée
    </button>
</div>
