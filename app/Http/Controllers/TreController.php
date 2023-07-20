<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use App\Http\Traits\Tree;
use App\Models\Investment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use stdClass;

class TreController extends Controller
{
    protected $position;

    public function index(String $id)
    {
        $user = User::findOrFail($id);

        $direct_childres = User::where('buyer_id', $user->id)->get();

        $referals_childrens =  $this->getChildren($direct_childres, 1);

        $directs = [];

        $indirects = [];

        foreach($referals_childrens as $user){
            $program = null;
            $date = null;
            if ($user->getProgram()) {
                $program = "FYT ".$user->getProgram()->getTypeName();
                $date = $user->getProgram()->created_at->format('Y-m-d');
            }

            $item = new stdClass();
            $item->id = $user->id;
            $item->user_name = $user->user_name;
            $item->email = $user->email;
            $item->children = $user->children;
            $item->status = $user->status;
            $item->program = $program ?? '';
            $item->date = $date ?? '';
            $directs[] = $item;
        }
        
        foreach($referals_childrens as $user)
        {
            foreach($user->children as $user) 
            {
                $program = null;
                $date = null;
                if ($user->getProgram()) {
                    $program = "FYT ".$user->getProgram()->getTypeName();
                    $date = $user->getProgram()->created_at->format('Y-m-d');
                }
                $item = new stdClass();
                $item->id = $user->id;
                $item->user_name = $user->user_name;
                $item->email = $user->email;
                $item->children = $user->children;
                $item->status = $user->status;
                $item->program = $program ?? '';
                $item->date = $date ?? '';
                $indirects[] = $item;
            }
        }

        $data = [
            'directs' => $directs,
            'indirects' => $indirects
        ];

        return response()->json($data);
    }

    public function referredTree($tree)
    {
        try {
            $base = Auth::user();
            $trees = $this->getDataEstructura(Auth::id(), $tree);

            foreach($trees as $treeChild){
                $childLicenses =  Investment::where('user_id', $treeChild->id)->where('status', 1)->first();

                if($childLicenses != NULL){
                    $treeChild->licence = $childLicenses->id;
                }
            }

            $licenses =  Investment::where('user_id', $base->id)->where('status', 1)->first();
            if($licenses != NULL){
                $base->licence = $licenses->LicensePackage->id;
            }

            $base->logoarbol = asset('images/avatars/1.png');
            $lastLevelActive = Level::where('status', '1')->orderBy('id', 'desc')->first();
            return view('genealogy.tree', compact('trees', 'base', 'lastLevelActive', 'tree'));
        } catch (\Throwable $th) {
            Log::error('Tree - index -> Error: ' . $th);
                abort(500, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function binario($base = NULL)
    {

        try {
            if($base == NULL)
            {
                $base = User::findOrFail( Auth::id() )->with('investment')->first();
            }
            $trees = $this->getDataEstructuraBinary($base->id, $tree = 2);
            foreach($trees as $tree){
                $childLicenses =  Investment::where('user_id', $tree->id)->where('status', 1)->with('LicensePackage')->first();

                if($childLicenses != NULL){
                    $tree->licence = $childLicenses->id;
                }
            }

            $licenses =  Investment::where('user_id', $base->id)->where('status', 1)->with('LicensePackage')->first();
            if($licenses != NULL){
                $base->licence = $licenses->LicensePackage->id;
            }

            $base->logoarbol = asset('images/avatars/1.png');
            $lastLevelActive = Level::where('status', '1')->orderBy('id', 'desc')->first();
            
            return view('binario.binario', compact('trees', 'base', 'lastLevelActive', 'tree'));
        } catch (\Throwable $th) {
            Log::error('Tree - index -> Error: ' . $th);
                abort(500, "Ocurrio un error, contacte con el administrador");
        }

        
    }

    public function buscar()
    {
        return view('genealogy.buscar');
    }

    public function levels()
    {
        $levels = Level::all();
        $lastLevelActive = Level::where('status', '1')->orderBy('id', 'desc')->first();
        return view('levels.index', compact('lastLevelActive', 'levels'));
    }

    public function activateLevels(Request $request)
    {
        $levels = Level::all();
        foreach ($levels as $level) {
            if ( $level->id <= $request->level) {
                $level->status = 1;
                $level->update();
            } else {
                $level->status = 0;
                $level->update();
            }
        }
        return back()->with('success', 'Los niveles han sido actualizados');
    }

    public function search(Request $request)
    {

        $user = User::find($request->id);
        if($user == null) { return back()->with('error', 'Usuario no Encontrado'); }

        $direct_childres = User::where('buyer_id', $user->id)->get();
        $referals_childrens = $this->getChildren($direct_childres, 1);

        return view('user.admin-referrals', compact('referals_childrens', 'direct_childres', 'user'));


        try {
            // titulo
            $trees = $this->getDataEstructura($request->id, null);
            //$type = ucfirst($type);
            $base = User::find($request->id);
            $base->logoarbol = asset('assets/img/sistema/favicon.png');


            return view('genealogy.tree', compact('trees', 'base'));
        } catch (\Throwable $th) {

            return back()->with('danger', 'El ID que ingreso no existe');
        }

    }

    public function searchBinary(Request $request)
    {
        try{
            $userCompare = User::find($request->id);
            $userAuth  = Auth::user();
            
            if( ($userCompare !== null) && ($userCompare->binary_id == $userAuth->id)){
                return $this->binario($userCompare);
            }

            $direct_children = User::where('binary_id', $userAuth->id)->get();

            $child = $this->binaryChild($direct_children, $nivel = 1, $request->id);
            if($child == 1){
               return $this->binario($userCompare);
            }else{
                return redirect('/red/binario')->with('error', 'Este usuario no pertenece a su red binaria');
            }

        }catch(\Throwable $th){
            return response()->json(['error' => 'El ID que ingreso no existe']);
        }
    }

    public function moretree($id)
    {
        try {
            // titulo
            $id = base64_decode($id);
            $trees = $this->getDataEstructura($id, null);
            //$type = ucfirst($type);
            $base = User::find($id);
            $base->logoarbol = asset('assets/img/sistema/favicon.png');

            $type_tm = 0;

            return view('genealogy.tree', compact('trees', 'base'));
        } catch (\Throwable $th) {
            Log::error('Tree - moretree -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function searchUnilevelTree(Request $request)
    {
        try {
            // titulo
            $id = $request->id;
            $trees = $this->getDataEstructura($id, null);
            //$type = ucfirst($type);
            $base = User::find($id);
            $base->logoarbol = asset('assets/img/sistema/favicon.png');

            foreach($trees as $treeChild){
                $childLicenses =  Investment::where('user_id', $treeChild->id)->where('status', 1)->first();

                if($childLicenses != NULL){
                    $treeChild->licence = $childLicenses->id;
                }
            }

            $licenses =  Investment::where('user_id', $base->id)->where('status', 1)->first();
            if($licenses != NULL){
                $base->licence = $licenses->LicensePackage->id;
            }

            if($trees !== NULL){
                $tree = 1;
                return view('genealogy.tree', compact('trees', 'base', 'tree'));
            }else{
                return redirect('/referred/tree/1')->with('error', 'Este usuario no pertenece a su red unilevel');
            }

        } catch (\Throwable $th) {
            Log::error('Tree - searchUnilevelTree -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    private function getDataEstructura($id, $tree)
    {
        try {

            $childres = $this->getData($id, 1, $tree);
            $trees = $this->getChildren($childres, 2);
            return $trees;
        } catch (\Throwable $th) {
            Log::error('Tree - getDataEstructura -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    private function getDataEstructuraBinary($id, $tree)
    {
        try {

            $childres = $this->getData($id, 1, $tree);
            $trees = $this->getChildrenBinary($childres, 2, $tree);
            return $trees;
        } catch (\Throwable $th) {
            Log::error('Tree - getDataEstructura -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function getChildren($users, $nivel)
    {

        try {
            if (!empty($users)) {
                foreach ($users as $user) {
                    $user->children = $this->getData($user->id, $nivel, null);

                    $this->getChildren($user->children, ($nivel+1));
                }
                return $users;
            }else{
                return $users;
            }
        } catch (\Throwable $th) {
            Log::error('Tree - getChildren -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function getChildrenBinary($users, $nivel, $tree)
    {
        try {
            if (!empty($users)) {
                foreach ($users as $user) {
                    $user->children = $this->getData($user->id, $nivel, $tree);

                    $this->getChildrenBinary($user->children, ($nivel+1), $tree);
                }
                return $users;
            }else{
                return $users;
            }
        } catch (\Throwable $th) {
            Log::error('Tree - getChildren -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function binaryChild($direct_childrens, $nivel, $userToCompare){
    
        $userCompare = User::find($userToCompare);

        if($userCompare == null){
            return response()->json(['error' => 'El usuario no existe']);
            //echo "El usuario no existe";
        }

        $bool = 0;
        try{
            if(count($direct_childrens) !== 0){
                foreach ($direct_childrens as $childrens){
                    if($userCompare->binary_id == $childrens->id){
                        return $bool = 1;
                    }
                    $childrens->children = $this->getData($childrens->id, $nivel, $tree = 2);
                    $this->binaryChild($childrens->children, $nivel+1, $userToCompare);
                }
            }else{
                return $bool = 0;
            }

        }catch(\Throwable $th){
            Log::error('Tree - getChildren -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
    
    public function getChildrenCount($users, $nivel, $count)
    {
        try {
            if (!empty($users))
            {
                foreach ($users as $user) {
                    $user->children = $this->getData($user->id, $nivel, null);
                    $nivel++;
                    $count++;
                    $count = $this->getChildrenCount($user->children, $nivel, $count);
                }
                return $count;
            }else{
                return $count;
            }
        } catch (\Throwable $th) {
            Log::error('Tree - getChildrenCount or Quantity -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Se trare la informacion de los hijos
     *
     * @param integer $id - id a buscar hijos
     * @param integer $nivel - nivel en que los hijos se encuentra
     * @param string $typeTree - tipo de arbol a usar
     * @return object
     */
    private function getData($id, $nivel, $tree)
    {
        try {
            $resul = User::where('buyer_id', $id)->get();
            if ($tree == 2) {
            $resul = User::where('binary_id', $id)->orderBy('binary_side', 'asc')->get();
            }
            foreach ($resul as $user) {
                $user->nivel = $nivel;
                $user->logoarbol = asset('assets/img/sistema/favicon.png');
            }
            return $resul;
        } catch (\Throwable $th) {
            Log::error('Tree - getData -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
    /**
     * Asigna la posición del usuario en caso de no tener un link de referido directo
     */
    public function getPosition(int $id, $lado)
    {
        try {
            // Log::debug('Tree - getPosition');
            $resul = 0;
            $ids = $this->getIDs($id, $lado);
            $limiteFila = 2;
            if ($lado != '') {
                if ($lado == 'L') {
                    if (count($ids) == 0) {
                        $resul = $id;
                    }else{
                        $this->verificarOtraPosition($ids, $limiteFila, $lado);
                        $resul = $this->position;
                    }
                }elseif($lado == 'R'){
                    if (count($ids) == 0) {
                        $resul = $id;
                    }else{
                        $this->verificarOtraPosition($ids, $limiteFila, $lado);
                        $resul = $this->position;
                    }
                }
            }else{
                if (count($ids) == 0) {
                    $resul = $id;
                }else{
                    $this->verificarOtraPosition($ids, $limiteFila, $lado);
                    $resul = $this->position;
                }
            }
            return $resul;
        } catch (\Throwable $th) {
            Log::error('Tree - getPosition -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
    /**
     * Asigna otra posición en caso de no estar disponible la primera.
     */
    public function verificarOtraPosition($arregloID, $limitePosicion, $lado)
    {
        try {
            // Log::debug('Tree - verificarOtraPosition');
            foreach ($arregloID as $item) {
                $ids = $this->getIDs($item['id'], $lado);
                if ($lado != '') {
                    if ($lado == 'L') {
                        if (count($ids) == 0) {
                            $this->position = $item['id'];
                            break;
                        }else{
                            $this->verificarOtraPosition($ids, $limitePosicion, $lado);
                        }
                    }elseif($lado == 'R'){
                        if (count($ids) == 0) {
                            $this->position = $item['id'];
                            break;
                        }else{
                            $this->verificarOtraPosition($ids, $limitePosicion, $lado);
                        }
                    }
                }else{
                    if (count($ids) == 0) {
                        $this->position = $item['id'];
                        break;
                    }else{
                        $this->verificarOtraPosition($ids, $limitePosicion, $lado);
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('Tree - verificarOtraPosition -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
    /**
     * Obtiene el id de la persona a la cual se le asigna el lado.
     */
    public function getIDs(int $id, string $lado)
    {
      try {
        // Log::debug('Tree - getIDs');
        if ($lado != '') {
            return User::where([
                ['binary_id', '=',$id],
                ['binary_side','=',$lado]
             ])->select('id')->orderBy('id')->get()->toArray();
          }else{
            return User::where([
                ['binary_id','=', $id],
             ])->select('id')->orderBy('id')->get()->toArray();
          }
      } catch (\Throwable $th) {
        Log::error('Tree - getIDs -> Error: '.$th);
        abort(403, "Ocurrio un error, contacte con el administrador");
      }
    }
}
