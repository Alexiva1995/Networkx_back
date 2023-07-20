<?php

namespace App\Traits;

use App\Http\Requests\FormularyStoreRequest;
use App\Models\Project;
use App\Models\Formulary;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

trait FormularyCreateTrait
{
  public function formularyCreate(FormularyStoreRequest $request)
  {
    Log::info("Entrando a formulary create", [$request]);
    $project = Project::find($request->project_id);

    Formulary::create([
      'project_id' => $project->id,
      'name' => $request->name,
      'login' => $request->login,
      'password' => Crypt::encryptString($request->password),
      'leverage' => $request->leverage,
      'balance' => $request->balance,
      'server' => $request->server,
      'date' => $request->date,
    ]);

    Log::info("Inicializando dataemail");
    $dataEmail = [
      'user' => $project->order->user->fullName(),
      'name' => $request->name,
      'login' => $request->login,
      'password' => $request->password,
      'leverage' => $request->leverage,
      'balance' => $request->balance,
      'server' => $request->server,
      'date' => $request->date
    ];

    Mail::send('mails.sendCredentials',  ['data' => $dataEmail], function ($msj) use ($project) {
      $msj->subject('Project Credentials.');
      $msj->to($project->order->user->email);
    });

    return response()->json(['status' => 'success', 'Successful, Formulary Created!', 201]);
  }
}
