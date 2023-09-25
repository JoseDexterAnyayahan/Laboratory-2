<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class MainController extends BaseController
{
    private $playlist;
    private $song;


    public function __construct()
    {
        $this->playlist = new \App\Models\PlaylistModel();
        $this->song = new \App\Models\SongModel();
    }
    public function index()
    {
        //
    }
    public function view()
    {
        $data = [
            'playlist' => $this->playlist->findAll(),
            'song' => $this->song->findAll(),

        ];
        return view('view', $data);
    }

    public function createPlaylist()
    {
        $data = [
            'name' => $this->request->getVar('pname'),
        ];

        $this->playlist->save($data);
        return redirect()->to('/view');
    }

    public function addsong()
    {

        $validationRules = [
            'song' => 'uploaded[song]|max_size[song,10240]|mime_in[song,audio/mpeg,audio/wav]',
        ];
        if ($this->validate($validationRules)) {


            $song = $this->request->getFile('song');
            $songname = $song->getName();
            $newName = $song->getRandomName();
            $song->move(ROOTPATH . 'uploads', $newName);
            $data = [
                'title' => $songname,
                'file_path' => $newName,
                //make sure tama names ng collumns
            ];
            $this->song->insert($data);
            return redirect()->to('/view');
        } else {
            $data['validation'] = $this->validator;
            echo "error";
        }
    }


}