<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale = App::currentLocale(); // en/sr

        //en-> sort po name_en
        //sr-> sort po name_sr

        if($locale=='en') {
            $data = Person::orderBy('name')->orderBy('surname')->paginate(5);
        } elseif($locale=='sr'){
            $data = Person::orderBy('name')->orderBy('surname')->paginate(5);
        } else{
            // all dovlaci sve podatke iz tabele genres
            $data = Person::paginate(5);
        }   

        return view('person.index', ['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('person.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|alpha',
            'surname' => 'required|alpha',
            'b_date' => 'required|date'
        ]);

        Person::create($request->all());
        
        $request->session()->flash('alertType', 'success');
        $request->session()->flash('alertMsg', 'Successfully added.');

        return redirect()->route('person.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        return view('person.edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $request->validate([
            'name' => ['required', 'alpha'],
            'surname' => ['required', 'alpha'],
            'b_date' => ['required', 'regex:/^[^A-Za-z]*$/']
        ]);

        $person->update($request->all());

        $request->session()->flash('alertType', 'success');
        $request->session()->flash('alertMsg', 'Successfully updated.');

        return redirect()->route('person.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();

        session()->flash('alertType', 'success');
        session()->flash('alertMsg', 'Successfully deleted.');

        return redirect()->route('person.index');
    }
}
