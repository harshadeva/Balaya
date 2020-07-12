<?php

namespace App\Http\Controllers;

use App\BeneficialCat;
use App\BeneficialDistrict;
use App\BeneficialElectionDivision;
use App\BeneficialGramasewaDivision;
use App\BeneficialPollingBooth;
use App\BeneficialVillage;
use App\Career;
use App\Category;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\NatureOfIncome;
use App\Office;
use App\PollingBooth;
use App\Post;
use App\PostAttachment;
use App\PostCareer;
use App\PostDistrict;
use App\PostEducation;
use App\PostElectionDivision;
use App\PostEthnicity;
use App\PostGramasewaDivision;
use App\PostIncome;
use App\PostPollingBooth;
use App\PostReligion;
use App\PostVillage;
use App\Religion;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *render 'create post' interface
     */
    public function index()
    {
        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict', Auth::user()->office->iddistrict)->get();

        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $categories = Category::where('status',1)->get();
        return view('post.create_post', ['title' => __('Create Post'),'categories'=>$categories,'electionDivisions' => $electionDivisions, 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);
    }

    public function store(Request $request)
    {
        $validationMessages = [
            'title_en.required' => 'Title in english should be provided!',
            'text_en.required' => 'Post text in english should be provided!',
            'cats.*.required' => 'Category should be provided!',
            'expireDate.required' => 'Expire date should be provided!',
            'expireDate.date' => 'Expire date format invalid!',
            'responsePanel.required' => 'Response panel should be provided!',
            'imageFiles.*.file' => 'Image file invalid!',
            'imageFiles.*.image' => 'Image file invalid!',
            'imageFiles.*.mimes' => 'Image file format invalid!',
            'imageFiles.*.max' => 'Image file should less than 5MB!',
            'videoFiles.*.file' => 'Video file invalid!',
            'videoFiles.*.mimes' => 'Video file format invalid!',
            'videoFiles.*.max' => 'Video file should less than 20MB!',
            'audioFiles.*.file' => 'Audio file invalid!',
            'audioFiles.*.mimes' => 'Audio file format invalid!',
            'audioFiles.*.max' => 'Audio file should less than 10MB!',
        ];

        $validator = \Validator::make($request->all(), [
            'title_en' => 'required',
            'text_en' => 'required',
            'cats.*' => 'required',
            'gender' => 'nullable',
            'expireDate' => 'required|date',
            'onlyOnce' => 'nullable',
            'responsePanel' => 'required',
            'imageFiles.*' => 'nullable|file|image|mimes:jpeg,png,gif,webp|max:5048',
            'videoFiles.*' => 'nullable|mimes:mp4,mov,ogg,qt | max:20000',
            'audioFiles.*' => 'nullable|mimes:mpga,wav | max:10000',


        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $user = Auth::user()->idUser;
        $office = Auth::user()->idoffice;

        $gramasewaArray = [];
        $pollingArray = [];
        $electionArray = [];

        //category validation

        $districtAll = false;
        $boothAll = false;
        $electionAll = false;
        $gramasewaAll = false;

        //category validation end

        //village level validation
        $villages = $request['villages'];
        if ($villages != null) {
            foreach ($villages as $id) {
                $selected = Village::find(intval($id));
                if ($selected != null) {
                    array_push($gramasewaArray, $selected->idgramasewa_division);
                } else {
                    return response()->json(['errors' => ['error' => 'Villages Invalid!']]);
                }
            }
        } else {
            $gramasewaAll = true;
        }
        //village level validation end

        //Gramasewa level validation
        $gramasewaDivisions = $request['gramasewaDivisions'];
        if ($gramasewaDivisions != null) {
            foreach ($gramasewaDivisions as $id) {
                $selected = GramasewaDivision::find(intval($id));
                if ($selected != null) {
                    array_push($pollingArray, $selected->idpolling_booth);
                } else {
                    return response()->json(['errors' => ['error' => 'Gramasewa divisions Invalid!']]);
                }
            }
        } else {
            $boothAll = true;
        }
        //Gramasewa level validation end

        //Polling booth level validation
        $pollingBooths = $request['pollingBooths'];
        if ($pollingBooths != null) {
            foreach ($pollingBooths as $id) {
                $selected = PollingBooth::find(intval($id));
                if ($selected != null) {
                    array_push($electionArray, $selected->idelection_division);
                } else {
                    return response()->json(['errors' => ['error' => 'Polling booths Invalid!']]);
                }
            }
        } else {
            $electionAll = true;
        }
        //Polling booth level validation end

        //Election division level validation
        $electionDivisions = $request['electionDivisions'];
        if ($electionDivisions == null) {
            $districtAll = true;
        }
        //Election division level validation end

        //-------------------------------------------Validation end---------------------------------------------------//

        //save in post table
        $post = new Post();
        $post->idUser = $user;
        $post->idoffice = Auth::user()->idoffice;
        $post->post_no = $post->nextPostNo($office);
        $post->title_en = $request['title_en'];
        $post->title_si = $request['title_si'];
        $post->title_si = $request['title_si'];
        $post->title_ta = $request['title_ta'];
        $post->text_en = $request['text_en'];
        $post->text_si = $request['text_si'];
        $post->text_ta = $request['text_ta'];
        $post->careers = $request['careers'] == null ? 0 : 1;
        $post->religions = $request['religions'] == null ? 0 : 1;
        $post->ethnicities = $request['ethnicities'] == null ? 0 : 1;
        $post->educations = $request['educations'] == null ? 0 : 1;
        $post->incomes = $request['incomes'] == null ? 0 : 1;
        $post->isOnce = $request['onlyOnce'] == 'on' ? 1 : 0;
        $post->job_sector = $request['jobSector'];
        $post->preferred_gender = $request['gender'];
        $post->minAge = $request['minAge'];
        $post->maxAge = $request['maxAge'];
        $post->response_panel = $request['responsePanel'];
        $post->response_panel = $request['responsePanel'];
        $post->categorized = 0;// uncategorized
        $post->status = 2;//pending post
        $post->expire_date = date('Y-m-d', strtotime($request['expireDate']));
        $post->save();
        //save in post table end

        //save in community tables
        $careers = $request['careers'];
        if ($careers != null) {
            foreach ($careers as $career) {
                $postCareer = new PostCareer();
                $postCareer->idPost = $post->idPost;
                $postCareer->idcareer = $career;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $religions = $request['religions'];
        if ($religions != null) {
            foreach ($religions as $religion) {
                $postCareer = new PostReligion();
                $postCareer->idPost = $post->idPost;
                $postCareer->idreligion = $religion;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $ethnicities = $request['ethnicities'];
        if ($ethnicities != null) {
            foreach ($ethnicities as $ethnicity) {
                $postCareer = new PostEthnicity();
                $postCareer->idPost = $post->idPost;
                $postCareer->idethnicity = $ethnicity;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $educations = $request['educations'];
        if ($educations != null) {
            foreach ($educations as $education) {
                $postCareer = new PostEducation();
                $postCareer->idPost = $post->idPost;
                $postCareer->ideducational_qualification = $education;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }

        $incomes = $request['incomes'];
        if ($incomes != null) {
            foreach ($incomes as $income) {
                $postCareer = new PostIncome();
                $postCareer->idPost = $post->idPost;
                $postCareer->idnature_of_income = $income;
                $postCareer->status = 1;
                $postCareer->save();

            }
        }
        //save in community tables end

        //save in hierarchy tables
        $electionDivisions = $request['electionDivisions'];
        if (!empty($electionDivisions)) {

            $postDistrict = new PostDistrict();
            $postDistrict->idPost = $post->idPost;
            $postDistrict->iddistrict = Auth::user()->office->iddistrict;
            $postDistrict->allChild = 0;
            $postDistrict->status = 1;
            $postDistrict->save();

            foreach ($electionDivisions as $electionDivision) {
                $postCareer = new PostElectionDivision();
                $postCareer->idPost = $post->idPost;
                $postCareer->idelection_division = $electionDivision;
                if (in_array($electionDivision, $electionArray)) {
                    $postCareer->allChild = 0;
                } else {
                    $postCareer->allChild = 1;
                }
                $postCareer->status = 1;
                $postCareer->save();
            }
        }
        else{
            $postDistrict = new PostDistrict();
            $postDistrict->idPost = $post->idPost;
            $postDistrict->iddistrict = Auth::user()->office->iddistrict;
            $postDistrict->allChild = 1;
            $postDistrict->status = 1;
            $postDistrict->save();
        }


        $pollingBooths = $request['pollingBooths'];
        if (!empty($pollingBooths)) {
            foreach ($pollingBooths as $id) {
                $postPolling = new PostPollingBooth();
                $postPolling->idPost = $post->idPost;
                $postPolling->idpolling_booth = $id;
                if (in_array($id, $pollingArray)) {
                    $postPolling->allChild = 0;
                } else {
                    $postPolling->allChild = 1;
                }
                $postPolling->status = 1;
                $postPolling->save();

            }
        }

        $gramasewaDivisions = $request['gramasewaDivisions'];
        if (!empty($gramasewaDivisions)) {
            foreach ($gramasewaDivisions as $id) {
                $postGramasewa = new PostGramasewaDivision();
                $postGramasewa->idPost = $post->idPost;
                $postGramasewa->idgramasewa_division = $id;
                if (in_array($id, $gramasewaArray)) {
                    $postGramasewa->allChild = 0;
                } else {
                    $postGramasewa->allChild = 1;
                }
                $postGramasewa->status = 1;
                $postGramasewa->save();

            }
        }

        $villages = $request['villages'];
        if (!empty($villages)) {
            foreach ($villages as $id) {
                $postVillage = new PostVillage();
                $postVillage->idPost = $post->idPost;
                $postVillage->idvillage = $id;
                $postVillage->status = 1;
                $postVillage->save();
            }
        }

        //save in hierarchy tables end

        //save post attachment

        //save images
        $images = $request->file('imageFiles');
        if (!empty($images)) {
            foreach ($images as $image) {
                $size = $image->getSize();
                $imageName = 'image' . uniqid() . rand(10, 100) . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/posts/images', $image, $imageName);
               ImageManagerStatic::make( asset('').'storage/' . Office::find($office)->random  . '/posts/images/'.$imageName)->save(
                   storage_path().'/app/public/' .  Office::find($office)->random  . '/posts/images/'.$imageName
                    , 30);

                $attachment = new PostAttachment();
                $attachment->idPost = $post->idPost;
                $attachment->attachment = $imageName;
                $attachment->file_type = 1;
                $attachment->size = $size;
                $attachment->status = 1;//active attachment
                $attachment->save();
            }
        }
        //save images end

        //save video
        $videos = $request->file('videoFiles');
        if (!empty($videos)) {
            foreach ($videos as $video) {
                $videoName = 'video' . uniqid() . rand(10, 100) . '.' . $video->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/posts/videos', $video, $videoName);

                $attachment = new PostAttachment();
                $attachment->idPost = $post->idPost;
                $attachment->attachment = $videoName;
                $attachment->file_type = 2;
                $attachment->size = $video->getSize();
                $attachment->status = 1;//active attachment
                $attachment->save();
            }
        }
        //save video end

        //save audio
        $audios = $request->file('audioFiles');
        if (!empty($audios)) {
            foreach ($audios as $audio) {
                $audioName = 'audio' . uniqid() . rand(10, 100) . '.' . $audio->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/posts/audios', $audio, $audioName);

                $attachment = new PostAttachment();
                $attachment->idPost = $post->idPost;
                $attachment->attachment = $audioName;
                $attachment->file_type = 3;
                $attachment->size = $audio->getSize();
                $attachment->status = 1;//active attachment
                $attachment->save();
            }
        }
        //save audio end

        //save in beneficial category tables
        $cats = $request['cats'];
        if ($cats != null) {
            foreach ($cats as $cat) {
                $subCategory = new BeneficialCat();
                $subCategory->idPost = $post->idPost;
                $subCategory->idcategory = $cat;
                $subCategory->status = 1;//default
                $subCategory->save();
            }
        }

        //save in beneficial category tables end

        //save in beneficial location tables

        $beniDistrict = new BeneficialDistrict();
        $beniDistrict->idPost = $post->idPost;
        $beniDistrict->iddistrict = Auth::user()->office->iddistrict;
        $beniDistrict->allChild = $districtAll;
        $beniDistrict->status = 1;
        $beniDistrict->save();

        if($electionDivisions != null) {
            foreach ($electionDivisions as $electionDivision) {
                $beniElectional = new BeneficialElectionDivision();
                $beniElectional->idPost = $post->idPost;
                $beniElectional->idelection_division = $electionDivision;
                $beniElectional->allChild = $electionAll;
                $beniElectional->status = 1;
                $beniElectional->save();
            }
        }

        if($pollingBooths != null) {
            foreach ($pollingBooths as $pollingBooth) {
                $beniPollingBooth = new BeneficialPollingBooth();
                $beniPollingBooth->idPost = $post->idPost;
                $beniPollingBooth->idpolling_booth = $pollingBooth;
                $beniPollingBooth->allChild = $boothAll;
                $beniPollingBooth->status = 1;
                $beniPollingBooth->save();
            }
        }

        if($gramasewaDivisions != null) {
            foreach ($gramasewaDivisions as $gramasewaDivision) {
                $beniGramasewa = new BeneficialGramasewaDivision();
                $beniGramasewa->idPost = $post->idPost;
                $beniGramasewa->idgramasewa_division = $gramasewaDivision;
                $beniGramasewa->allChild = $gramasewaAll;
                $beniGramasewa->status = 1;
                $beniGramasewa->save();
            }
        }

        if($villages != null) {
            foreach ($villages as $village) {
                $beniVillage = new BeneficialVillage();
                $beniVillage->idPost = $post->idPost;
                $beniVillage->idvillage = $village;
                $beniVillage->status = 1;
                $beniVillage->save();
            }
        }

        //save in beneficial location tables end


        //save post attachment end
        return response()->json(['success' => 'Post published Successfully!']);

    }

    public function pending(Request $request)
    {
        $posts = Post::where('status', 2)->where('idoffice', Auth::user()->idoffice)->latest()->get();
        return view('post.pending_posts', ['title' => __('Pending Posts'), 'posts' => $posts]);

    }

    public function active(Request $request)
    {
        $posts = Post::where('status', 1)->where('idoffice', Auth::user()->idoffice)->latest()->get();
        return view('post.active_posts', ['title' => __('Active Posts'), 'posts' => $posts]);

    }

    public function rejected(Request $request)
    {
        $posts = Post::where('status', 0)->where('idoffice', Auth::user()->idoffice)->latest()->get();
        return view('post.rejected_posts', ['title' => __('Rejected Posts'), 'posts' => $posts]);

    }

    public function view(Request $request)
    {
        if(Auth::user()->iduser_role == 8){
            $posts = Post::where('idUser', Auth::user()->idUser)->where('idoffice', Auth::user()->idoffice)->latest()->get();
        }
        else{
            $posts = Post::where('idoffice', Auth::user()->idoffice)->latest()->get();

        }
        return view('post.view_posts', ['title' => __('View Posts'), 'posts' => $posts]);

    }

    public function showAdmin(Request $request)
    {
        $id = intval($request['id']);
        $post = Post::with(['attachments'])->find($id);

        return response()->json(['success' => $post]);

    }

    public function viewByCategory(Request $request)
    {
        $category = intval($request['category']);
        $posts = Post::where('idoffice', Auth::user()->office->idoffice)->where('status', 1)->whereHas('beneficialCategory', function ($q) use ($category) {
            $q->where('idcategory', $category);
        })->latest()->get();
        return view('post.view_posts', ['title' => __('View Posts'), 'posts' => $posts]);

    }

    public function publish(Request $request)
    {
        $posts = Post::where('idPost', $request['id'])->where('idoffice', Auth::user()->idoffice)->first();

            $posts->status = 1;
            $posts->save();
        return response()->json(['success' => 'success']);

    }

    public function reject(Request $request)
    {
        $posts = Post::where('idPost', $request['id'])->where('idoffice', Auth::user()->idoffice)->first();
            $posts->status = 0;
            $posts->save();
        return response()->json(['success' => 'success']);

    }

}
