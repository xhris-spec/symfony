import { Component, Input, OnInit } from '@angular/core';
import { Champion } from '../models/champion.model';
import { LanguageService } from '../services/language.service';

@Component({
  selector: 'app-champion-item',
  templateUrl: './champion-item.component.html',
})
export class ChampionItemComponent implements OnInit {
  @Input() champion: Champion = {
    splashart: '',
    name: '',
    slug: '',
    description: '',
    abilities: [],
    role: []
  };

  constructor(private languageService: LanguageService) { }

  ngOnInit(): void {
    this.languageService.currentLanguage.subscribe(language => {
      console.log('Language changed to', language);
    });
  }
}
