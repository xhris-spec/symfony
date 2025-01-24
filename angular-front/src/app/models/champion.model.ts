export interface Ability {
  name: string;
  description: string;
  image: string;
  video: string;
}

export interface Champion {
  name: string;
  description: string;
  abilities: Ability[];
  splashart: string;
  slug: string;
  role: string[];
}
