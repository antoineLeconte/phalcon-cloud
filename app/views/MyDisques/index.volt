<p>
    Debug : <br/>
    Id User : {{ dIdUser }}
</p>

<h2>Mes disques -> {{ loginUser }}</h2>
{% for disque in disques %}
    <div class="well well-sm">
        <h3><span class="glyphicon glyphicon-hdd"></span> {{ disque.nom }}</h3>
        <span>{{ occupationDisque[disque.id].occupation }} / {{ infosDisques[disque.id].quota }} {{ infosDisques[disque.id].unite }}</span>

    </div>
{% endfor %}