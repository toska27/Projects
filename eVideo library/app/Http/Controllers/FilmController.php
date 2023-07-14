<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Person;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            /*$request->validate([
                'rating' => 'nullable|integer|between:1,10',
                'year_from' => 'nullable|date_format:Y|before_or_equal:today',
                'year_to' => 'nullable|date_format:Y|before_or_equal:today',
                'time_from' => 'nullable|integer|min:1',
                'time_to' => 'nullable|integer|min:1'
            ]);

            if($request->year_from && $request->year_to){
                $request->validate([
                    'year_to'=>'gte:year_from'
                ]); 
            }
            
            if($request->time_from && $request->time_to){
                $request->validate([
                    'time_to'=>'gte:time_from'
                ]); 
            }*/
            $rule=[
                'rating' => 'nullable|integer|between:1,10',
                'year_from' => 'nullable|date_format:Y|before_or_equal:today',
                'year_to' => 'nullable|date_format:Y|before_or_equal:today',
                'time_from' => 'nullable|integer|min:1',
                'time_to' => 'nullable|integer|min:1'
            ];

            if($request->year_from && $request->year_to){
                $rule['year_to'].='|gte:year_from';
            }
            
            if($request->time_from && $request->time_to){
                $rule['time_to'].='|gte:time_from';
            }
            $request->validate($rule); 
            
            $rating = $request->rating;
            $yearFrom = $request->year_from;
            $yearTo = $request->year_to;
            $timeFrom = $request->time_from;
            $timeTo = $request->time_to;
            $name = $request->name;
            $genre = $request->genre;
            $star = $request->star;

            $datas = Film::when($rating, function (Builder $query) use ($rating) {
                $query->where('rating', '>=', $rating);
            })
            ->when($yearFrom, function (Builder $query) use ($yearFrom) {
                $query->where('year', '>=', $yearFrom);
            })
            ->when($yearTo, function (Builder $query) use ($yearTo) {
                $query->where('year', '<=', $yearTo);
            })
            ->when($timeFrom, function (Builder $query) use ($timeFrom) {
                $query->where('running_h', '>=', $timeFrom);
            })
            ->when($timeTo, function (Builder $query) use ($timeTo) {
                $query->where(function (Builder $query) use ($timeTo) {
                    $query->where('running_h', '<', $timeTo)
                        ->orWhere(function (Builder $query) use ($timeTo) {
                            $query->where('running_h', '=', $timeTo)
                            ->whereNull('running_m');
                        });
                });
                //select * from films where running_h<GORNJE_GRANICE ili (running_h=GORNJE_GRANICE and running_m is null)
            })
            /*->toSql();
            echo $datas; exit;*/

            ->when($name, function (Builder $query) use ($name) {
                $query->where('name', 'like', "%".$name."%");
            })
            ->when($genre, function (Builder $query) use ($genre) {
                $query->whereHas('genres', function (Builder $query) use ($genre){
                    $query->where('id', $genre);
                });
            })
            ->when($star, function (Builder $query) use ($star) {
                $query->whereHas('stars', function (Builder $query) use ($star){
                    $query->where('id', $star);
                });
            })


            ->get();


            
            $populateData = $request->all();
            
        }else{
            $datas=Film::all();
            $populateData = [];
        }

        $genres=Genre::all();
        $people=Person::all();
        return view('film.index', compact('datas', 'populateData', 'genres', 'people'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //dd(Genre::all()->sortBy('name'));
        $genres = Genre::all()->sortBy('name');
        //$genres = Genre::orederBy('name_en')->get();

        $people = Person::all()->sortBy('fullName');

        return view('film.create', compact('genres', 'people'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //1. validacija podataka
        $request->validate([
            'name' => 'required',            
            'running_h' => 'nullable|numeric|min:1|integer',
            'running_m' => 'nullable|numeric|between:1,59|integer',
            'year' => 'required|date_format:Y|before_or_equal:today',
            'rating' => 'required|decimal:1|between:1,10',
            'directors' => 'required|array',
            'writers' => 'required|array',
            'stars' => 'required|array',
            'genres' => 'required|array',
            'image' => 'image|between:1,2048'
        ]);

        //2. upis u tabelu Film
        $film = Film::create($request->only('name','running_h','running_m','year','rating'));
        //3. upis u tabelu film_genre
        $film->genres()->attach($request->genres);
        //4. upis u tabelu film_director, film_writer, film_star
        $film->directors()->attach($request->directors);
        $film->writers()->attach($request->writers);
        $film->stars()->attach($request->stars);

        //proveravamo da li forma ima sliku i da li je slika validno otpremljena
        if($request->hasFile('image') && $request->file('image')->isValid()){
            //generisemo naziv slike id filma i ekstenzija fajla
            $imgName = $film->id . '.' . $request->file('image')->extension();
            //smestamo fajl u folder public/film_images
            Storage::disk('public')
            ->putFileAs('film_images', $request->file('image'), $imgName);
         
            //u bazi belezimo putanju od public foldera
            $film->image = 'film_images/'.$imgName;
            $film->save();
         }
         

        return redirect()->route('film.show', ['film' => $film]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Film $film)
    {
        //ideja je da sort zanrova bude po abecedi
        $zanrovi = $film->genres; //OVO VRACA KOLEKCIJU moze odmah da se koristi za prolazak
        $upit = $film->genres(); //OVO VRACA UPIT
        $zanrovi2 = $upit->get(); //TEK SADA IMATE KOLEKCIJU ZA PROLAZAK

        $zanroviSort = $film->genres->sortBy('name'); //dozvoljeno je da se navedu vestacki atributi
        $upit = $film->genres()->orderBy('name'); //OVO NE MOZE JER NAME NEMA U BAZI
        $zanroviOreder = $film->genres()->orderBy('name_en')->get(); //ovo moze 



        //dd($film->genres);
        return view('film.show', ['film'=>$film]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Film $film)
    {
        $genres = Genre::all()->sortBy('name');
        $people = Person::all()->sortBy('fullName');

        return view('film.edit', compact('film', 'genres', 'people'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Film $film)
    {
        $request->validate([
            'name' => 'required',            
            'running_h' => 'nullable|numeric|min:1|integer',
            'running_m' => 'nullable|numeric|between:1,59|integer',
            'year' => 'required|date_format:Y|before_or_equal:today',
            'rating' => 'required|decimal:1|between:1,10',
            'directors' => 'required|array',
            'writers' => 'required|array',
            'stars' => 'required|array',
            'genres' => 'required|array',
            'image' => 'image|between:1,2048'
        ]);

        $film->update($request->only('name','running_h','running_m','year','rating'));
        $film->genres()->sync($request->genres);
        $film->directors()->sync($request->directors);
        $film->writers()->sync($request->writers);
        $film->stars()->sync($request->stars);

        if($request->hasFile('image') && $request->file('image')->isValid()){ 
            if($film->image && Storage::disk('public')->exists($film->image)){
                Storage::disk('public')->delete($film->image);
            }           
            $imgName = $film->id . '.' . $request->file('image')->extension();
            Storage::disk('public')
            ->putFileAs('film_images', $request->file('image'), $imgName);        
            $film->image = 'film_images/'.$imgName;
            $film->save();
        }elseif($request->image_remove=="yes"){
            if($film->image && Storage::disk('public')->exists($film->image)){
                Storage::disk('public')->delete($film->image);
            }
            $film->image = null;
            $film->save();
        }
        return view('film.show', ['film'=>$film]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
        try{
            $film->delete();

            session()->flash('alertType', 'success');
            session()->flash('alertMsg', 'Successfully deleted.');

            return redirect()->route('film.index');
        }
        catch(Exception $e) {
            session()->flash('alertType', 'danger');
            session()->flash('alertMsg', 'Cannot be deleted.');

            return redirect()->route('film.index');
        }
    }
}