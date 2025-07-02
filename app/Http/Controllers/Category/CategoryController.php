<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function electronic()
    {
        // Ambil semua service dengan kategori Electronic
        $electronicServices = Service::where('category', 'Electronic')
                                   ->with('user') // Load relasi user untuk informasi teknisi
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        
        return view('Category.Electronic.index', compact('electronicServices'));
    }

    public function fashion()
    {
        // Ambil semua service dengan kategori Fashion
        $fashionServices = Service::where('category', 'Fashion')
                                 ->with('user') // Load relasi user untuk informasi teknisi
                                 ->orderBy('created_at', 'desc')
                                 ->get();
        
        return view('Category.Fashion.index', compact('fashionServices'));
    }

    public function gadget()
    {
        $gadgetServices = Service::where('category', 'Gadget')
                                 ->with('user') // Load relasi user untuk informasi teknisi
                                 ->orderBy('created_at', 'desc')
                                 ->get();
        
        return view('Category.Gadget.index', compact('gadgetServices'));
    }

    public function otomotif()
    {
        $otomotifServices = Service::where('category', 'Otomotif')
                                 ->with('user') // Load relasi user untuk informasi teknisi
                                 ->orderBy('created_at', 'desc')
                                 ->get();
        
        return view('Category.Otomotif.index', compact('otomotifServices'));
    }

    public function other()
    {
         $otherServices = Service::where('category', 'Other')
                                 ->with('user') // Load relasi user untuk informasi teknisi
                                 ->orderBy('created_at', 'desc')
                                 ->get();
        
        return view('Category.Other.index', compact('otherServices'));
    }

    
}
