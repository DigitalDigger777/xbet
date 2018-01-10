<?php
/**
 * Created by PhpStorm.
 * User: korman
 * Date: 10.01.18
 * Time: 1:57
 */

namespace App\Utils;

use GuzzleHttp\Client;

class Xbet
{
    /**
     * @var string
     */
    private $lang = 'en';

    /**
     * @var int
     */
    private $currencyId = 3;

    /**
     * @var string
     */
    private $url = 'https://1xbetua.com/BetExchange/dashboard';

    /**
     * @var \stdClass
     */
    private $data;

    public function loadData()
    {
        $client = new Client();
        $response = $client->request('GET', $this->url, [
            'query' => [
                'currencyId' => $this->currencyId,
                'lng' => $this->lang
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $this->data = json_decode($response->getBody(), true);
        } else {
            throw new \Exception('Server return ' . $response->getStatusCode() . ' error code');
        }

        return $this;
    }

    public function parse()
    {
        $result = [];
        $leagues = $this->data['SInf'][0]['CInf'];

        foreach ($leagues as $keyLeague => $league) {
            $result[$keyLeague]['league']['name'] = $league['ChN'];

            //Gaming List (GL)
            $gamingList = $league['GL'];

            foreach ($gamingList as $keyGame => $game) {

                $result[$keyLeague]['league']['gameList'][$keyGame]['gameId']    = $game['GI'];
                $result[$keyLeague]['league']['gameList'][$keyGame]['gameName']  = $game['GN'];
                $result[$keyLeague]['league']['gameList'][$keyGame]['gameTime']  = $game['St'];
                $eventList = $game['EL'][0];

                $result[$keyLeague]['league']['gameList'][$keyGame]['events'][0]['k1']['back'] = $eventList[0]['KL'][0]['K'];
                $result[$keyLeague]['league']['gameList'][$keyGame]['events'][0]['k1']['lay'] = $eventList[0]['KL'][1]['K'];

                $result[$keyLeague]['league']['gameList'][$keyGame]['events'][0]['draw']['back'] = $eventList[1]['KL'][0]['K'];
                $result[$keyLeague]['league']['gameList'][$keyGame]['events'][0]['draw']['lay'] = $eventList[1]['KL'][1]['K'];

                $result[$keyLeague]['league']['gameList'][$keyGame]['events'][0]['k2']['back'] = $eventList[2]['KL'][0]['K'];
                $result[$keyLeague]['league']['gameList'][$keyGame]['events'][0]['k2']['lay'] = $eventList[2]['KL'][1]['K'];
            }
        }
        return $result;
    }

}