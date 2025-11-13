const cycleSelect = document.getElementById('cycle');
const classeSelect = document.getElementById('classe');

const classes = {
    "Maternelle":["4ans A","4ans B","4ans C","4ans D","4ans E","4ans F","4ans G", "5ans A","5ans B","5ans C","5ans D","5ans E","5ans F","5ans G"],
    "Primaire":["1 ère Primaire","2 ème Primaire","3 ème Primaire","4 ème","5 ème","6 ème"],
    "Secondaire":["7 ème","8 ème"],
    "Humanité":["1 ère Humanité","2 ème Humanité","3 ème Humanité","4 ème Humanité"]
};

cycleSelect.addEventListener('change', function(){
    const selected = this.value;
    classeSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
    classes[selected].forEach(c => {
        const opt = document.createElement('option');
        opt.value = c;
        opt.textContent = c;
        classeSelect.appendChild(opt);
    });
});
