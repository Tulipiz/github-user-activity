<?php

class GitHubService
{
    public function usersEvents($nome)
    {
        $url = "https://api.github.com/users/" . $nome . "/events";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: MeuAppPHP',
            'Accept: application/vnd.github.v3+json'
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
            curl_close($ch);
            exit;
        }

        curl_close($ch);

        $events = json_decode($response, true);

        if (!is_array($events)) {
            echo "Não foi possível obter os eventos para o usuário \"$nome\".\n";
            return;
        }
        foreach ($events as $event) {
            $this->typeEvent($event);
        }
    }
    private function typeEvent($event)
    {



        $tipo = $event['type'] ?? 'Desconhecido';
        $repo = $event['repo']['name'] ?? 'Repositório não informado';


        if ($tipo === "PushEvent") {
            $commits = count($event['payload']['commits'] ?? []);

            echo "- Pushed " . $commits . " commit(s) to " . $repo . ".\n";
        }
        if ($tipo === "WatchEvent") {
            $action = $event['payload']['action'];
            if ($action === "started") {
                echo "- Starred " . $repo . ".\n";
            }
        }
        if ($tipo === "IssuesEvent") {
            $action = $event['payload']['action'];
            if ($action === "opened") {
                echo "- Opened a new issue in " . $repo . "\n";
            }
            if ($action === "closed") {
                echo "- Closed a new issue in " . $repo . "\n";
            }
        }
        if ($tipo === "CreateEvent") {
            $refType = $event['payload']['ref_type'];
            if ($refType === "branch") {
                echo "- Created a new Branch in " . $repo . "\n";
            }
            if ($refType === "repository") {
                echo "- Created a new repository: " . $repo . "\n";
            }
        }
    }
}
