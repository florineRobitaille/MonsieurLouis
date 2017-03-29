<?php
/**
 * Welcome controller
 *
 * @author David Carr - dave@novaframework.com
 * @version 3.0
 */

namespace App\Controllers;

use App\Core\Controller;

use App\Models\Chanson;
use App\Models\Playlist;

use Nova\Support\Facades\Auth;
use Nova\Support\Facades\Input;
use Nova\Support\Facades\Redirect;
use Nova\Support\Facades\Request;


use View;

/**
 * Sample controller showing 2 methods and their typical usage.
 */
class Welcome extends Controller
{

    /**
     * Create and return a View instance.
     */
    public function index()
    {
        $message = __('Hello, welcome from the welcome controller! <br/>
this content can be changed in <code>/app/Views/Welcome/Welcome.php</code>');
        
        
        
        /*$c = new Chanson();
        $c ->nom = "test";
        $c ->duree = "00:02:00";
        $c ->fichier = "blabla";
        $c ->post_date = "2017-07-03";
        $c ->style = "Rock";
        $c ->utilisateur_id = 1;
        $c ->save();*/
        if(Auth::check()) 
            $pls = Playlist::whereRaw('utilisateur_id=?', array(Auth::id()))->get();
        else $pls = false;
        
        return View::make('Welcome/Welcome')
            ->shares('title', __('Welcome'))
            ->with('welcomeMessage', $message)
            ->with('all',Chanson::all())
            ->with('playlists',$pls);
    }

    
    public function formupload(){
        return View::make('Welcome/Formupload')
            ->shares('title', 'nouvelle');
    }
    
    public function creechanson(){
        
        if(Input::has('nom') &&
          Input::has('style') &&
          Input::hasFile('chanson') &&
          Input::file('chanson')->isValid()){
            $file = Input::file("chanson")->getClientOriginalName();
            $f = Input::file("chanson")->move("assets/images/".Auth::user()->username,$file);
            
            
            $c = new Chanson();
            $c ->nom = Input::get('nom');
            $c ->style = Input::get('style');
            $c ->fichier = "/".$f;
            $c ->utilisateur_id = Auth::id();
            $c ->duree = "";
            $c ->post_date = date("Y-m-d h:i:s");
            $c ->save();
            return Redirect::to('/');
            
        
        }
        
        echo "<pre>"; 
        
        print_r($_POST);
        
        echo "<br />";
        print_r($_FILES);
        
        echo "</pre>";
        die(1);
    }
    /**
     * Create and return a View instance.
     */
    public function subPage()
    {
        $message = __('Hello, welcome from the welcome controller and subpage method! <br/>
This content can be changed in <code>/app/Views/Welcome/SubPage.php</code>');

        return $this->getView()
            ->shares('title', __('Subpage'))
            ->withWelcomeMessage($message);
    }
    
    public function utilisateur($id){
        $u = User::find($id);
        if($u==false)
            return View::make('Error/404')
                ->shares('title', 'non trouve');
        $playlists = 
            Playlist::whereRaw('utilisateur_id=?', array($id))->get();
        $all = 
            Canson::whereRaw('utilisateur_id=?', array($id))->get();
        return View::make('Welcome/utilisateur')
            ->shares('title', __('About'))
            ->with('user', '$u')
            ->with('all', '$all')
            ->with('playlists', $playlists);
    }


        public function about()
    {
        return View::make('Welcome/About')
            ->shares('title', __('About'))
            ->with('nom', 'Louis');
    }
    
    public function param($id)
    {
        $c = Chanson::findOrFail($id);
        if($c==false)
            return View::make('Error/404')
                ->shares('title', 'non trouve');
        
        return View::make('Welcome/Param')
            ->shares('title', __('Param'))
            ->with('chanson', $c);
    }

    public function creeplaylist()
    {
        $p = new Playlist();
        $p ->nom = Input::get('nom');
        $p->utilisateur_id = Auth::id();
        $p ->save();
        if(Request::ajax()) { //si on utilise l'ajax pour crer une palylist
                $playlists = 
            Playlist::whereRaw('utilisateur_id=?', array(Auth::id()))->get(); //alors, on récupère les informations corespondantes en relation avec l'user
            return View::fetch('Welcome/Playlist',array("playlists"=>$playlists)); // et en afficher que la playlist
        }
       return Redirect::to('/'); // avant de rediriger à la racine
    }
    
}
