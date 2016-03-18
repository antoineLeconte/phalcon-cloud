<h2>Mes disques -> {{ loginUser }}</h2>
{% for disque in disques %}
    <div class="well well-sm">
        <h3>
            <span class="glyphicon glyphicon-hdd"></span> {{ disque.nom }}
            <span class="badge">{{ infosDisques[disque.id]['occupation'] }} {{ infosDisques[disque.id]['uniteOccupation'] }}/
                {{ infosDisques[disque.id]['tailleMax'] }} {{ infosDisques[disque.id]['uniteTailleMax'] }}</span>
        </h3>
        {{ q['barreOccupation' ~ disque.id] }}
        {{ q['boutonOuverture' ~ disque.id] }}
    </div>
{% endfor %}

{{ script_foot }}