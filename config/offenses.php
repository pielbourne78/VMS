<?php

return [
    'sanction_types' => [
        'A' => 'Warning',
        'B' => 'Community Service',
        'C' => 'Suspension',
        'D' => 'Non-readmission',
        'E' => 'Exclusion',
        'F' => 'Expulsion',
    ],

    // Same path for every minor offense (1st to 5th)
    'minor_path' => [
        'Recorded Verbal Reprimand',
        'Written Reprimand + 3 hrs Community Service',
        'Written Reprimand + 6 hrs Community Service',
        'Written Reprimand + 9 hrs Community Service',
        'Written Reprimand + 12 hrs Community Service',
    ],

    // code => [category, title, path (major only)]
    'offenses' => [
        'MI1' => ['minor', 'Not wearing/refusing to present school I.D.'],
        'MI2' => ['minor', 'Entering class without I.D. and/or not in proper uniform'],
        'MI3' => ['minor', 'Cross dressing'],
        'MI4' => ['minor', 'Use of school facilities/equipment without permission'],
        'MI5' => ['minor', 'Derogatory language/gestures that damage the institution\'s image'],
        'MI6' => ['minor', 'Public Display of Affection (PDA)'],
        'MI7' => ['minor', 'Eating or drinking inside laboratories'],
        'MI8' => ['minor', 'Littering'],
        'MI9' => ['minor', 'Entering restricted area without permission'],

        'MA1'  => ['major', 'Sexual harassment / acts of lasciviousness', ['D','E']],
        'MA2'  => ['major', 'Sexual immorality and obscenity', ['D','E']],
        'MA3'  => ['major', 'Fraternity, gang, sorority, hazing', ['D','E']],
        'MA4'  => ['major', 'Possession of / under the influence of liquor', ['C','C','D','E']],
        'MA5'  => ['major', 'Possession of / under the influence of illegal drugs', ['E']],
        'MA6'  => ['major', 'Possession of cigarette / smoking in uniform or with I.D.', ['B','C','D','E']],
        'MA7'  => ['major', 'Possession of deadly weapon or harmful objects', ['C','D','E']],
        'MA8'  => ['major', 'Theft', ['B','C','D','E']],
        'MA9'  => ['major', 'Extortion', ['C','D','E']],
        'MA10' => ['major', 'Bribery', ['C','C','D','E']],
        'MA11' => ['major', 'Violence, assault or physical injuries', ['B','C','D','E']],
        'MA12' => ['major', 'Slandering / labeling', ['B','C','D','E']],
        'MA13' => ['major', 'Forging, falsification or tampering of documents', ['C','D','E']],
        'MA14' => ['major', 'Forming/participating in union or rebellion against administration', ['D','E']],
        'MA15' => ['major', 'Willful disobedience / insubordination', ['B','B','C','C','D']],
        'MA16' => ['major', 'Any form of corruption', ['C','D','E']],
        'MA17' => ['major', 'Dishonesty (borrowing I.D., cheating, plagiarism)', ['B','B','C','D','E']],
        'MA18' => ['major', 'Destruction of school property / vandalism', ['B','C','D','E']],
        'MA19' => ['major', 'Pornographic materials or sites', ['B','C','D','E']],
        'MA20' => ['major', 'Any form of gambling', ['B','C','D','E']],
        'MA21' => ['major', 'Unauthorized solicitation/activities', ['B','C','D','E']],
        'MA22' => ['major', 'Cyber crime', ['B','C','D','E']],
        'MA23' => ['major', 'Invasion of privacy', ['B','C','D','E']],
        'MA24' => ['major', 'Violating fire safety policy', ['B','C','D','E']],
        'MA25' => ['major', 'Causing/creating commotion', ['B','B','C','D','E']],
        'MA26' => ['major', 'Bullying (physical, relational, cyber)', ['B','B','C','D','E']],
    ],
];