import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Champion } from '../models/champion.model';
import { Fancybox } from "@fancyapps/ui";
import { LanguageService } from 'src/app/services/language.service';

@Component({
  selector: 'app-champion',
  templateUrl: './champion.component.html',
})
export class ChampionComponent implements OnInit {
  champion: Champion | undefined;
  slug: string = '';
  loading = false;


  constructor(private http: HttpClient, private route: ActivatedRoute, private languageService: LanguageService) {}

  ngOnInit() {
    this.slug = this.route.snapshot.paramMap.get('slug') || '';
    this.languageService.currentLanguage.subscribe(language => {
      this.loading = true;
      this.http.get<Champion>(`http://127.0.0.1:8003/api/${language}/` + this.slug).subscribe( (data) => {
        this.champion = data;
        this.loading = false;
      })
    });

    Fancybox.bind("[data-fancybox='video-gallery']");
  }
}
